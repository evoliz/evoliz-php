<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\EvolizHelper;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\InvalidTypeException;
use Evoliz\Client\Exception\PaginationException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\BaseModel;
use Evoliz\Client\Response\APIResponse;

abstract class BaseRepository
{
    /**
     * @var Config Configuration for API usage
     */
    private $config;

    /**
     * @var string Model's base endpoint
     */
    private $baseEndpoint;

    /**
     * @var string Reference model
     */
    private $baseModel;

    /**
     * @var string Associated response model
     */
    private $responseModel;

    /**
     * @var string[] Array of headers to pass to Guzzle queries to override current headers
     */
    private $overloadedHeaders;

    /**
     * Setup the different parameters for the API requests
     * @param Config $config Configuration for API usage
     * @param string $baseModel Reference model
     * @param string $baseEndpoint Base endpoint
     * @param string $responseModel Response model
     * @throws ConfigException
     */
    public function __construct(Config $config, string $baseModel, string $baseEndpoint, string $responseModel)
    {
        $this->config = $config->authenticate();
        $this->baseModel = $baseModel;
        $this->baseEndpoint = $baseEndpoint;
        $this->responseModel = $responseModel;

        $this->overloadedHeaders = [
            'Authorization' => 'Bearer ' . $this->config->getAccessToken()->getToken()
        ];
    }

    /**
     * Return a list of requested object visible by the current User, according to visibility restriction set in user profile
     * @param array $query Additional query parameters
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function list(array $query = [])
    {
        $response = $this->config->getClient()->get('api/v1/' . $this->baseEndpoint, [
            'query' => $query,
            'headers' => $this->overloadedHeaders
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        $this->handleError($responseBody, $response->getStatusCode());

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
     * @param int $objectid Object id
     * @return mixed|string Response in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function detail(int $objectid)
    {
        $response = $this->config->getClient()->get('api/v1/' . $this->baseEndpoint . '/' . $objectid, [
            'headers' => $this->overloadedHeaders
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        $this->handleError($responseBody, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new $this->responseModel($responseBody);
        } else {
            return json_encode($responseBody);
        }
    }

    /**
     * Create a new object with given data
     * @param BaseModel $object Object to create
     * @return mixed|string Response in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function create(BaseModel $object)
    {
        $response = $this->config->getClient()->post('api/v1/' . $this->baseEndpoint, [
            'body' => json_encode($this->buildPayload($object)),
            'headers' => $this->overloadedHeaders
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        $this->handleError($responseBody, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new $this->responseModel($responseBody);
        } else {
            return json_encode($responseBody);
        }
    }

    /**
     * Move to the requested page of the resource
     * @param APIResponse|string $object Object Response to list() function
     * @param string $requestedPage Requested page ('first', 'last', 'previous', 'next' or 'perso')
     * @param int|null $pageNumber Requested page number (used only if $requestedPAge = 'perso')
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested uri does not exist
     * @throws InvalidTypeException|ResourceException|PaginationException
     */
    public function paginate($object, string $requestedPage, int $pageNumber = null)
    {
        if (!($object instanceof APIResponse) && !EvolizHelper::is_json($object) ) {
            throw new InvalidTypeException('Error : The given object is not of the right type', 401);
        }

        if (!in_array($requestedPage, ['first', 'last', 'previous', 'next', 'perso'])) {
            throw new PaginationException('Error : The requestedPage attribute must be one of first, last, previous, next or perso', 401);
        }

        if ($this->config->getDefaultReturnType() === 'JSON') {
            $decodedObject = json_decode($object, true);
            $data = [];

            foreach ($decodedObject['data'] as $objectData) {
                $data[] = new $this->responseModel($objectData);
            }

            $object = new APIResponse($data, $decodedObject['links'], $decodedObject['meta']);
        }

        switch ($requestedPage) {
            case 'first':
            case 'last':
            case 'next':
                $requestedUri = $object->links[$requestedPage] ?? null;
                break;
            case 'previous':
                $requestedUri = $object->links['prev'] ?? null;
                break;
            case 'perso':
                if ($pageNumber === null){
                    throw new PaginationException('Error : The pageNumber attribute must be filled if the requestedPage attribute is set to perso', 401);
                }
                $requestedUri = preg_replace(['/[?]page=[0-9]+/', '/[&]page=[0-9]+/'], ['?page=' . $pageNumber, '&page=' . $pageNumber], $object->links['first']);
                break;
            default:
                $requestedUri = null;
        }

        if ($requestedUri === null) {
            return null;
        }

        $response = $this->config->getClient()->get($requestedUri, [
            'headers' => $this->overloadedHeaders
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        $this->handleError($responseBody, $response->getStatusCode());

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
     * Handle response error returned by the API
     * @param array $responseBody Array of response error and message
     * @param int $statusCode HTTP Status code
     * @throws ResourceException
     */
    private function handleError(array $responseBody, int $statusCode)
    {
        if (!($statusCode >= 200 && $statusCode < 300))
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
    }

    /**
     * Mapping of the request payload to create the entry
     * @param object|array|string $object Object to create
     * @return array
     */
    private function buildPayload($object): array
    {
        $payload = [];
        foreach ($object as $attribute => $value) {
            if (is_object($value) || is_array($value)) {
                $payload[$attribute] = $this->buildPayload($value);
            } elseif ($value !== null) {
                $payload[$attribute] = $value;
            }
        }
        return $payload;
    }
}
