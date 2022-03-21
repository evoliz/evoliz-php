<?php

namespace Evoliz\Client;

use Evoliz\Client\Exception\ConfigException;
use GuzzleHttp\Client;

class Config
{
    const BASE_URI = "https://www.evoliz.io/";
    const OBJECT_RETURN_TYPE = "OBJECT";
    const JSON_RETURN_TYPE = "JSON";

    /**
     * @var Client Guzzle active client
     */
    private $client;

    /**
     * @var Client Guzzle active client configuration
     */
    private $clientConfig;

    /**
     * @var bool Setup Guzzle Client verify parameter for SSL verification
     */
    private $verifySSL;

    /**
     * @var integer User's companyid
     */
    private $companyId;

    /**
     * @var string User's public key given in the app
     */
    private $publicKey;

    /**
     * @var string User's secret key given when the API credentials are created in the app
     */
    private $secretKey;

    /**
     * @var AccessToken Token that should be used for authentication as a Bearer Authorization Token
     */
    private $accessToken;

    /**
     * @var string Resources default return type
     * Possible values = 'OBJECT' or 'JSON'
     */
    private $defaultReturnType = self::OBJECT_RETURN_TYPE;

    /**
     * Setup the configuration for API usage
     * @param int $companyId User's companyid
     * @param string $publicKey User's public key given in the app
     * @param string $secretKey User's secret key given when the API credentials are created in the app
     * @param bool $verifySSL Param to setup Guzzle options for SSL verification
     * @throws \Exception|ConfigException
     */
    public function __construct(int $companyId, string $publicKey, string $secretKey, bool $verifySSL = true)
    {
        $this->companyId = $companyId;
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
        $this->verifySSL = $verifySSL;

        $this->clientConfig = [
            'base_uri' => self::BASE_URI,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'verify' => $this->verifySSL
        ];

        if ($this->hasValidCookieAccessToken()) {
            $decodedToken = json_decode($_COOKIE['evoliz_token_' . $this->companyId]);
            $this->accessToken = new AccessToken($decodedToken->access_token, $decodedToken->expires_at);
        } else {
            $this->accessToken = $this->login();
        }

        $this->clientConfig['headers'] += [
            'Authorization' => 'Bearer ' . $this->accessToken->getToken()
        ];

        $this->client = new Client($this->clientConfig);
    }

    /**
     * Get the active Guzzle client
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Get user's companyid
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * Set user's companyid
     * @param int $companyId
     */
    public function setCompanyId(int $companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Get the Access Token linked to the connection
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * Check if the user is already identified and restarts the process if this is not the case
     * @return Config
     * @throws \Exception|ConfigException
     */
    public function authenticate(): Config
    {
        if (!$this->hasValidAccessToken()) {
            $this->accessToken = $this->login();

            $this->client = new Client($this->clientConfig);
        }

        return $this;
    }

    /**
     * Get resources default return type
     * @return string
     */
    public function getDefaultReturnType(): string
    {
        return $this->defaultReturnType;
    }

    /**
     * Set resources default return type
     * @param string $returnType Expected type of return (OBJECT or JSON)
     * @return void
     * @throws ConfigException
     */
    public function setDefaultReturnType(string $returnType)
    {
        if (!in_array($returnType, [self::JSON_RETURN_TYPE, self::OBJECT_RETURN_TYPE])) {
            throw new ConfigException("Error : The given return type is not valid", 400);
        }

        $this->defaultReturnType = $returnType;
    }

    /**
     * Login the user with given public and secret keys
     * @return AccessToken
     * @throws ConfigException|\Exception
     */
    protected function login(): AccessToken
    {
        $this->client = new Client($this->clientConfig);

        $loginResponse = $this->client->post('api/login', [
            'body' => json_encode([
                'public_key' => $this->publicKey,
                'secret_key' => $this->secretKey
            ])
        ]);

        $responseBody = json_decode($loginResponse->getBody()->getContents());

        if ($loginResponse->getStatusCode() !== 200) {
            $errorMessage = $responseBody->error . ' : ' . $responseBody->message;
            throw new ConfigException($errorMessage, $loginResponse->getStatusCode());
        }

        if (isset($responseBody->access_token)) {
            // Cookie Storage
            setcookie('evoliz_token_' . $this->companyId, json_encode([
                'access_token' => $responseBody->access_token,
                'expires_at' => $responseBody->expires_at
            ]));

            $accessToken = new AccessToken($responseBody->access_token, $responseBody->expires_at);
        } else {
            throw new ConfigException('The access token has not been recovered', 422);
        }

        return $accessToken;
    }

    /**
     * Check if there is a valid access token stored in the cookies
     * @throws \Exception
     */
    private function hasValidCookieAccessToken(): bool
    {
        return isset($_COOKIE['evoliz_token_' . $this->companyId])
            && new \DateTime(json_decode($_COOKIE['evoliz_token_' . $this->companyId])->expires_at)
            > new \DateTime('now');
    }

    /**
     * Check if there is a valid access token stored in the cookies or the config
     * @throws \Exception
     */
    private function hasValidAccessToken(): bool
    {
        return $this->hasValidCookieAccessToken()
            || (isset($this->accessToken)
                && !$this->accessToken->isExpired());
    }
}
