<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\PaginationException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\HttpClient;
use Evoliz\Client\Model\BaseModel;
use Evoliz\Client\Response\APIResponse;

abstract class BaseRepository
{
    /**
     * @var Config Configuration for API usage
     */
    protected $config;

    /**
     * @var string Model's base endpoint
     */
    protected $baseEndpoint;

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
     *
     * @param  Config $config        Configuration for API usage
     * @param  string $baseEndpoint  Base endpoint
     * @param  string $responseModel Response model
     * @throws ConfigException
     */
    public function __construct(Config $config, string $baseEndpoint, string $responseModel)
    {
        $this->config = $config->authenticate();
        $this->baseEndpoint = 'api/v1/' . $baseEndpoint;
        $this->responseModel = $responseModel;
    }

    /**
     * Return a list of requested object visible by the current User
     * According to visibility restriction set in user profile
     *
     * @param  array $query Additional query parameters
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function list(array $query = [])
    {
        $response = HttpClient::getInstance()->get(
            $this->baseEndpoint,
            [
                'query' => $query
            ]
        );

        $responseContent = $response->getBody()->getContents();
        $this->lastResponse = json_decode($responseContent, true);

        $this->handleError($this->lastResponse, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            $data = [];

            foreach ($this->lastResponse['data'] as $objectData) {
                $data[] = new $this->responseModel($objectData);
            }

            return new APIResponse($data, $this->lastResponse['links'], $this->lastResponse['meta']);
        } else {
            return $responseContent;
        }
    }

    /**
     * Return an object by its speficied id
     *
     * @param  int $objectid Object id
     * @return mixed|string Response in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function detail(int $objectid)
    {
        $response = HttpClient::getInstance()->get($this->baseEndpoint . '/' . $objectid);

        $responseContent = $response->getBody()->getContents();

        $decodedContent = json_decode($responseContent, true);

        $this->handleError($decodedContent, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new $this->responseModel($decodedContent);
        } else {
            return $responseContent;
        }
    }

    /**
     * Create a new object with given data
     *
     * @param  BaseModel $object Object to create
     * @return mixed|string Response in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function create(BaseModel $object)
    {
        $response = HttpClient::getInstance()->post(
            $this->baseEndpoint,
            [
                'body' => json_encode($this->buildPayload($object))
            ]
        );

        $responseContent = $response->getBody()->getContents();

        $decodedContent = json_decode($responseContent, true);

        $this->handleError($decodedContent, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new $this->responseModel($decodedContent);
        } else {
            return $responseContent;
        }
    }

    /**
     * Accessor for the number of pages of the resource
     *
     * @return int Number of pages
     * @throws ResourceException
     */
    public function getNumberOfPages(): int
    {

        if (!isset($this->lastResponse)) {
            $this->list();
        }

        return $this->lastResponse['meta']['last_page'];
    }

    /**
     * Move to the first page of the resource
     *
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @return null Return null if the requested page does not exist
     * @throws PaginationException|ResourceException
     */
    public function firstPage()
    {
        return $this->paginate('first');
    }

    /**
     * Move to the last page of the resource
     *
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @return null Return null if the requested page does not exist
     * @throws PaginationException|ResourceException
     */
    public function lastPage()
    {
        return $this->paginate('last');
    }

    /**
     * Move to the previous page of the resource
     *
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @return null Return null if the requested page does not exist
     * @throws PaginationException|ResourceException
     */
    public function previousPage()
    {
        return $this->paginate('prev');
    }

    /**
     * Move to the next page of the resource
     *
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @return null Return null if the requested page does not exist
     * @throws PaginationException|ResourceException
     */
    public function nextPage()
    {
        return $this->paginate('next');
    }

    /**
     * Move to the requested page of the resource
     *
     * @param  int $pageNumber Requested page number
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException
     */
    public function page(int $pageNumber)
    {
        if (!isset($this->lastResponse)) {
            $this->list();
        }

        $query = $this->retrieveQueryParameters($this->lastResponse['links']['first']);
        $query['page'] = $pageNumber;

        return $this->list($query);
    }

    /**
     * Move to the requested page of the resource
     *
     * @param  string $requestedPage Requested page ('first', 'last', 'prev' or 'next')
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @return null Return null if the requested uri does not exist
     * @throws PaginationException|ResourceException
     */
    private function paginate(string $requestedPage)
    {
        if (!in_array($requestedPage, ['first', 'last', 'prev', 'next'])) {
            $errorMessage = 'Error : The requestedPage attribute must be one of first, last, prev or next';
            throw new PaginationException($errorMessage, 401);
        }

        if (!isset($this->lastResponse)) {
            if (in_array($requestedPage, ['next', 'prev'])) {
                return null;
            }
            $this->list();
        }

        // If the requested page doesn't exist, return null
        if ($this->lastResponse['links'][$requestedPage] === null) {
            return null;
        }

        $query = $this->retrieveQueryParameters($this->lastResponse['links'][$requestedPage]);

        return $this->list($query);
    }

    /**
     * @Todo   : Think about a design pattern for error handling
     * Handle response error returned by the API
     * @param  array $responseBody Array of response error and message
     * @param  int   $statusCode   HTTP Status code
     * @throws ResourceException
     */
    protected function handleError(array $responseBody, int $statusCode)
    {
        if (!($statusCode >= 200 && $statusCode < 300)) {
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
     *
     * @param  \stdClass|array $object Object to create
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
     * Retrieve query parameters from an uri
     *
     * @param  string $uri Requested uri with parameters to retrieve
     * @return array Array of query parameters
     */
    private function retrieveQueryParameters(string $uri): array
    {
        parse_str(parse_url($uri, PHP_URL_QUERY), $query);
        return $query;
    }
}
