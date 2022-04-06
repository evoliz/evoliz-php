<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\EvolizHelper;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\InvalidTypeException;
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
     * @var string Reference model
     */
    private $baseModel;

    /**
     * @var string Associated response model
     */
    private $responseModel;

    /**
     * @var string Authentication token retrieved from the API
     */
    private $authenticationToken;

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

        $this->authenticationToken = 'Bearer ' . $this->config->getAccessToken()->getToken();
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
            'headers' => [
                'Authorization' => $this->authenticationToken
            ]
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
            'headers' => [
                'Authorization' => $this->authenticationToken
            ]
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
            'headers' => [
                'Authorization' => $this->authenticationToken
            ]
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
     * If you are already on the first page, return the resource as is
     * @param APIResponse|string $object Object Response to list() function
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException|InvalidTypeException
     */
    public function firstPage($object)
    {
        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            if (!($object instanceof APIResponse)) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            if ($object->meta['current_page'] !== 1) {
                $response = $this->config->getClient()->get($object->links['first'], [
                    'headers' => [
                        'Authorization' => $this->authenticationToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);

                $this->handleError($responseBody, $response->getStatusCode());

                $data = [];
                foreach ($responseBody['data'] as $objectData) {
                    $data[] = new $this->responseModel($objectData);
                }

                return new APIResponse($data, $responseBody['links'], $responseBody['meta']);
            }
        } else {
            if (!EvolizHelper::is_json($object)) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            $decodedObject = json_decode($object);

            if ($decodedObject->meta->current_page !== 1) {
                $response = $this->config->getClient()->get($decodedObject->links->first, [
                    'headers' => [
                        'Authorization' => $this->authenticationToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);

                $this->handleError($responseBody, $response->getStatusCode());

                return json_encode($responseBody);
            }
        }

        return $object;
    }

    /**
     * Move to the first last of the resource
     * If you are already on the last page, return the resource as is
     * @param APIResponse|string $object Object Response to list() function
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException|InvalidTypeException
     */
    public function lastPage($object)
    {
        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            if (!($object instanceof APIResponse)) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            if ($object->meta['current_page'] < $object->meta['last_page']) {
                $response = $this->config->getClient()->get($object->links['last'], [
                    'headers' => [
                        'Authorization' => $this->authenticationToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);

                $this->handleError($responseBody, $response->getStatusCode());

                $data = [];
                foreach ($responseBody['data'] as $objectData) {
                    $data[] = new $this->responseModel($objectData);
                }

                return new APIResponse($data, $responseBody['links'], $responseBody['meta']);
            }
        } else {
            if (!EvolizHelper::is_json($object)) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            $decodedObject = json_decode($object);

            if ($decodedObject->meta->current_page < $decodedObject->meta->last_page) {
                $response = $this->config->getClient()->get($decodedObject->links->last, [
                    'headers' => [
                        'Authorization' => $this->authenticationToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);

                $this->handleError($responseBody, $response->getStatusCode());

                return json_encode($responseBody);
            }
        }

        return $object;
    }

    /**
     * Move to the previous page of the resource
     * If there is no previous page, return the resource as is
     * @param APIResponse|string $object Object Response to list() function
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException|InvalidTypeException
     */
    public function previousPage($object)
    {
        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            if (!($object instanceof APIResponse)) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            if ($object->meta['current_page'] > 1) {
                $response = $this->config->getClient()->get($object->links['prev'], [
                    'headers' => [
                        'Authorization' => $this->authenticationToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);

                $this->handleError($responseBody, $response->getStatusCode());

                $data = [];
                foreach ($responseBody['data'] as $objectData) {
                    $data[] = new $this->responseModel($objectData);
                }

                return new APIResponse($data, $responseBody['links'], $responseBody['meta']);
            }
        } else {
            if (!EvolizHelper::is_json($object)) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            $decodedObject = json_decode($object);

            if ($decodedObject->meta->current_page > 1) {
                $response = $this->config->getClient()->get($decodedObject->links->prev, [
                    'headers' => [
                        'Authorization' => $this->authenticationToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);

                $this->handleError($responseBody, $response->getStatusCode());

                return json_encode($responseBody);
            }
        }

        return $object;
    }

    /**
     * Move to the next page of the resource
     * If there is no next page, return the resource as is
     * @param APIResponse|string $object Object Response to list() function
     * @return APIResponse|string Objects list in the expected format (OBJECT or JSON)
     * @throws ResourceException|InvalidTypeException
     */
    public function nextPage($object)
    {
        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            if (!($object instanceof APIResponse)) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            if ($object->meta['current_page'] < $object->meta['last_page']) {
                $response = $this->config->getClient()->get($object->links['next'], [
                    'headers' => [
                        'Authorization' => $this->authenticationToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);

                $this->handleError($responseBody, $response->getStatusCode());

                $data = [];
                foreach ($responseBody['data'] as $objectData) {
                    $data[] = new $this->responseModel($objectData);
                }

                return new APIResponse($data, $responseBody['links'], $responseBody['meta']);
            }

        } else {
            if (!EvolizHelper::is_json($object)) {
                throw new InvalidTypeException('Error : The given object is not of the right type', 401);
            }

            $decodedObject = json_decode($object);

            if ($decodedObject->meta->current_page < $decodedObject->meta->last_page) {
                $response = $this->config->getClient()->get($decodedObject->links->next, [
                    'headers' => [
                        'Authorization' => $this->authenticationToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);

                $this->handleError($responseBody, $response->getStatusCode());

                return json_encode($responseBody);
            }
        }

        return $object;
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
}
