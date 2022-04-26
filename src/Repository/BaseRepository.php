<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\InvalidTypeException;
use Evoliz\Client\Exception\PaginationException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\BaseModel;
use Evoliz\Client\Response\APIResponse;

abstract class BaseRepository
{
    const HTTP_SUCCESS_CODES = [200, 201, 202, 203, 204, 206, 207, 208, 226];

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
     * @var APIResponse|string Last query response
     */
    private $lastResponse;

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
            'query' => $query
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        $this->handleError($responseBody, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            $data = [];

            foreach ($responseBody['data'] as $objectData) {
                $data[] = new $this->responseModel($objectData);
            }
            $this->lastResponse = new APIResponse($data, $responseBody['links'], $responseBody['meta']);
        } else {
            $this->lastResponse = json_encode($responseBody);
        }
        return $this->lastResponse;
    }

    /**
     * Return an object by its speficied id
     * @param int $objectid Object id
     * @return mixed|string Response in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function detail(int $objectid)
    {
        $response = $this->config->getClient()->get('api/v1/' . $this->baseEndpoint . '/' . $objectid);

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
     * Accessor for the number of pages of the resource
     * @return int Number of pages
     * @throws InvalidTypeException|ResourceException
     */
    public function getNumberOfPages(): int
    {

        if (!isset($this->lastResponse)) {
            $this->lastResponse = $this->list();
        }

        $object = $this->formatObject($this->lastResponse);

        return $object->meta['last_page'];
    }

    /**
     * Move to the first page of the resource
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested page does not exist
     * @throws InvalidTypeException
     * @throws PaginationException
     * @throws ResourceException
     */
    public function firstPage()
    {
        return $this->paginate('first');
    }

    /**
     * Move to the last page of the resource
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested page does not exist
     * @throws PaginationException|InvalidTypeException|ResourceException
     */
    public function lastPage()
    {
        return $this->paginate('last');
    }

    /**
     * Move to the previous page of the resource
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested page does not exist
     * @throws InvalidTypeException
     * @throws PaginationException
     * @throws ResourceException
     */
    public function previousPage()
    {
        return $this->paginate('prev');
    }

    /**
     * Move to the next page of the resource
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested page does not exist
     * @throws InvalidTypeException
     * @throws PaginationException
     * @throws ResourceException
     */
    public function nextPage()
    {
        return $this->paginate('next');
    }

    /**
     * Move to the requested page of the resource
     * @param int $pageNumber Requested page number
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws InvalidTypeException
     * @throws ResourceException
     */
    public function page(int $pageNumber)
    {
        $object = $this->formatObject($this->lastResponse);

        parse_str(parse_url($object->links['first'], PHP_URL_QUERY), $query);
        $query['page'] = $pageNumber;

        return $this->getRequestUri($object->meta['path'], $query);
    }

    /**
     * Move to the requested page of the resource
     * @param string $requestedPage Requested page ('first', 'last', 'prev' or 'next')
     * @return APIResponse|string|null Objects list in the expected format (OBJECT or JSON) or null if the requested uri does not exist
     * @throws InvalidTypeException
     * @throws PaginationException
     * @throws ResourceException
     */
    private function paginate(string $requestedPage)
    {
        if (!in_array($requestedPage, ['first', 'last', 'prev', 'next'])) {
            throw new PaginationException('Error : The requestedPage attribute must be one of first, last, prev or next', 401);
        }

        if (!isset($this->lastResponse)) {
            $this->lastResponse = $this->list();
        }

        $object = $this->formatObject($this->lastResponse);

        $requestedUri = $object->links[$requestedPage] ?? null;

        if ($requestedUri === null) {
            return null;
        }

        return $this->getRequestUri($requestedUri);
    }

    /**
     * Handle response error returned by the API
     * @param array $responseBody Array of response error and message
     * @param int $statusCode HTTP Status code
     * @throws ResourceException
     */
    private function handleError(array $responseBody, int $statusCode)
    {
        if (!in_array($statusCode, self::HTTP_SUCCESS_CODES))
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
     * @param mixed|string $object Object to create
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
    private function formatObject($object): APIResponse
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
    private function getRequestUri(string $requestedUri, array $query = null)
    {
        if (isset($query)) {
            $response = $this->config->getClient()->get($requestedUri, [
                'query' => $query
            ]);
        } else {
            $response = $this->config->getClient()->get($requestedUri);
        }

        $responseContent = $response->getBody()->getContents();

        $decodedContent = json_decode($responseContent, true);

        $this->handleError($decodedContent, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            $data = [];

            foreach ($decodedContent['data'] as $objectData) {
                $data[] = new $this->responseModel($objectData);
            }

            $this->lastResponse = new APIResponse($data, $decodedContent['links'], $decodedContent['meta']);
        } else {
            $this->lastResponse = $responseContent;
        }
        return $this->lastResponse;
    }
}
