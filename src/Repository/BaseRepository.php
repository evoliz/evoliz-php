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
     * @var string Associated response model
     */
    private $responseModel;

    /**
     * Setup the different parameters for the API requests
     * @param Config $config Configuration for API usage
     * @param string $baseEndpoint Base endpoint
     * @param string $responseModel Response model
     * @throws ConfigException
     */
    public function __construct(Config $config, string $baseEndpoint, string $responseModel)
    {
        $this->config = $config->authenticate();
        $this->baseEndpoint = 'api/v1/' . $baseEndpoint;
        $this->responseModel = $responseModel;
    }

    /**
     * Return a list of requested object visible by the current User, according to visibility restriction set in user profile
     * @param array $query Additional query parameters
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function list(array $query = [])
    {
        $response = $this->config->getClient()->get($this->baseEndpoint, [
            'query' => $query
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
        $response = $this->config->getClient()->get($this->baseEndpoint . '/' . $objectid);

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
        $response = $this->config->getClient()->post($this->baseEndpoint, [
            'body' => json_encode($this->buildPayload($object))
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
     * Move to the first page of the resource
     * @param APIResponse|string $object Object Response to list() function
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested page does not exist
     * @throws PaginationException|InvalidTypeException|ResourceException
     */
    public function firstPage($object)
    {
        return $this->paginate($object, 'first');
    }

    /**
     * Move to the last page of the resource
     * @param APIResponse|string $object Object Response to list() function
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested page does not exist
     * @throws PaginationException|InvalidTypeException|ResourceException
     */
    public function lastPage($object)
    {
        return $this->paginate($object, 'last');
    }

    /**
     * Move to the previous page of the resource
     * @param APIResponse|string $object Object Response to list() function
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested page does not exist
     * @throws PaginationException|InvalidTypeException|ResourceException
     */
    public function previousPage($object)
    {
        return $this->paginate($object, 'prev');
    }

    /**
     * Move to the next page of the resource
     * @param APIResponse|string $object Object Response to list() function
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested page does not exist
     * @throws PaginationException|InvalidTypeException|ResourceException
     */
    public function nextPage($object)
    {
        return $this->paginate($object, 'next');
    }

    /**
     * Move to the requested page of the resource
     * @param APIResponse|string $object Object Response to list() function
     * @param int $pageNumber Requested page number
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException|InvalidTypeException
     */
    public function page($object, int $pageNumber)
    {
        $object = $this->formatedObject($object);

        $requestedUri = preg_replace(['/[?]page=[0-9]+/', '/[&]page=[0-9]+/'], ['?page=' . $pageNumber, '&page=' . $pageNumber], $object->links['first']);

        return $this->requestForPaginate($requestedUri);
    }

    /**
     * Move to the requested page of the resource
     * @param APIResponse|string $object Object Response to list() function
     * @param string $requestedPage Requested page ('first', 'last', 'prev' or 'next')
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested uri does not exist
     * @throws InvalidTypeException|PaginationException|ResourceException
     */
    private function paginate($object, string $requestedPage)
    {
        if (!in_array($requestedPage, ['first', 'last', 'prev', 'next'])) {
            throw new PaginationException('Error : The requestedPage attribute must be one of first, last, prev or next', 401);
        }

        $object = $this->formatedObject($object);

        $requestedUri = $object->links[$requestedPage] ?? null;

        if ($requestedUri === null) {
            return null;
        }

        return $this->requestForPaginate($requestedUri);
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
     * @param \stdClass|array $object Object to create
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

    /**
     * Format an object response to OBJECT format
     * @param APIResponse|string $object Object Response to guzzle query
     * @return APIResponse Objects list in the OBJECT format
     * @throws InvalidTypeException
     */
    private function formatedObject($object): APIResponse
    {
        if ($this->config->getDefaultReturnType() === 'JSON') {
            $decodedObject = json_decode($object, true);

            if ($decodedObject === null) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            $data = [];

            foreach ($decodedObject['data'] as $objectData) {
                $data[] = new $this->responseModel($objectData);
            }

            $object = new APIResponse($data, $decodedObject['links'], $decodedObject['meta']);
        }

        if (!($object instanceof APIResponse)) {
            throw new InvalidTypeException('Error : The given object is not of the right type', 401);
        }

        return $object;
    }

    /**
     * Guzzle request with custom uri for pagination
     * @param string $requestedUri Requested pagination uri
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function requestForPaginate(string $requestedUri)
    {
        $response = $this->config->getClient()->get($requestedUri);

        $responseContent = $response->getBody()->getContents();

        $decodedContent = json_decode($responseContent, true);

        $this->handleError($decodedContent, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            $data = [];

            foreach ($decodedContent['data'] as $objectData) {
                $data[] = new $this->responseModel($objectData);
            }

            return new APIResponse($data, $decodedContent['links'], $decodedContent['meta']);
        } else {
            return $responseContent;
        }
    }
}
