<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\InvalidTypeException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\Response\APIResponse;

abstract class BaseRepository
{
    /**
     * @var Config
     */
    private $config;

    private $baseEndpoint;

    private $baseModel;

    private $responseModel;

    /**
     * @throws \Exception
     */
    public function __construct(Config $config, $baseModel)
    {
        $this->config = $config->authenticate();
        $this->baseModel = $baseModel;
        $this->baseEndpoint = $this->baseModel::BASE_ENDPOINT;
        $this->responseModel = $this->baseModel::RESPONSE_MODEL;
    }

    /**
     * Return a list of requested object visible by the current User, according to visibility restriction set in user profile
     * @param array $query
     * @return APIResponse|string objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function list(array $query = [])
    {
        $response = $this->config->getClient()->get('api/v1/' . $this->baseEndpoint, [
            'query' => $query
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() !== 200) {
           $this->handleError($responseBody, $response->getStatusCode());
        }

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            $data = [];

            foreach ($responseBody['data'] as $objectData) {
                $data[] = new $this->responseModel($objectData);
            }

            return new APIResponse($data, $responseBody['links'], $responseBody['meta']);
        } else {
            return json_encode($responseBody);
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
            $this->handleError($responseBody, $response->getStatusCode());
        }

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new $this->responseModel($responseBody);
        } else {
            return json_encode($responseBody);
        }
    }

    /**
     * @throws ResourceException
     */
    private function handleError($responseBody, $statusCode)
    {
        $errorMessage = $responseBody['error'] . ' : ';
        if (is_array($responseBody['message'])) {
            foreach ($responseBody['message'] as $error) {
                $errorMessage .= '<br>' . $error[0];
            }
        } else {
            $errorMessage .= $responseBody['message'];
        }
        throw new ResourceException($errorMessage, $statusCode);
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
