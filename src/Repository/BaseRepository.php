<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\InvalidTypeException;
use Evoliz\Client\Exception\ResourceException;

abstract class BaseRepository
{
    /**
     * @var Config
     */
    protected $config;

    protected $baseEndpoint;

    protected $baseModel;

    protected $responseModel;

    /**
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        $this->config = $config->authenticate();
        $this->retrieveModelInformations();
    }

    /**
     * Return a list of requested object visible by the current User, according to visibility restriction set in user profile
     * @param array $query
     * @return array|string objects list in the expected format (OBJECT or JSON)
     */
    public function list(array $query = [])
    {
        $response = $this->config->getClient()->get('api/v1/' . $this->baseEndpoint, [
            'query' => $query
        ]);

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            $objects = [];
            foreach (json_decode($response->getBody()->getContents(), true)['data'] as $objectData) {
                $objects[] = new $this->responseModel($objectData);
            }

            return $objects;
        } else {
            return $response->getBody()->getContents();
        }
    }

    /**
     * Return an object by its speficied id
     * @param int $objectid
     * @return mixed|string Object in the expected format (OBJECT or JSON)
     */
    public function detail(int $objectid)
    {
        $response = $this->config->getClient()->get('api/v1/' . $this->baseEndpoint . '/' . $objectid);

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new $this->responseModel(json_decode($response->getBody()->getContents(), true));
        } else {
            return $response->getBody()->getContents();
        }
    }

    /**
     * Create a new object with given data
     * @param $object
     * @return mixed|string
     * @throws InvalidTypeException|ResourceException
     */
    public function create($object)
    {
        if ($object instanceof $this->responseModel) {
            $object = new $this->baseModel((array) $object);
        } elseif (!($object instanceof $this->baseModel)) {
            throw new InvalidTypeException('Error : The given object is not of the right type', 401);
        }

        $response = $this->config->getClient()->post('api/v1/' . $this->baseEndpoint, [
            'body' => json_encode($this->mapPayload($object))
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() !== 201) {
            $errorMessage = $responseBody['error'] . ' : ';
            if ($response->getStatusCode() === 400) {
                foreach ($responseBody['message'] as $error) {
                    $errorMessage .= '<br>' . $error[0];
                }
            } else {
                $errorMessage .= $responseBody['message'];
            }
            throw new ResourceException($errorMessage, $response->getStatusCode());
        }

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new $this->responseModel($responseBody);
        } else {
            return json_encode($responseBody);
        }
    }

    private function retrieveModelInformations()
    {
        $repository = explode('\\', get_class($this));

        $this->baseModel = 'Evoliz\Client\Model\\' . str_replace('Repository', '', end($repository));
        $this->baseEndpoint = $this->baseModel::BASE_ENDPOINT;
        $this->responseModel = $this->baseModel::RESPONSE_MODEL;
    }

    /**
     * Mapping of the request payload to create the entry
     * @param $object
     * @return array
     */
    private function mapPayload($object): array
    {
        $payload = [];
        foreach ($object as $attribute => $value) {
            if (isset($object->$attribute)) {
                $payload[$attribute] = $value;
            }
        }
        return $payload;
    }
}
