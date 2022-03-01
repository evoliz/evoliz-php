<?php

namespace Evoliz\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Config
{
    const BASE_URI = "https://www.evoliz.io/";

    /**
     * @var Client Guzzle active client
     */
    private $client;

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
    private $defaultReturnType = 'OBJECT'; // @Todo: Check the different packages in order to see if we can't use an enum

    /**
     * Setup the configuration for API usage
     *
     * @param int $companyId
     * @param string $publicKey
     * @param string $secretKey
     * @throws \Exception
     */
    public function __construct(int $companyId, string $publicKey, string $secretKey)
    {
        $this->companyId = $companyId;
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;

        if ($this->hasValidCookieAccessToken()) {
            $decodedToken = json_decode($_COOKIE['evoliz_token_' . $this->companyId]);
            $this->accessToken = new AccessToken($decodedToken->access_token, $decodedToken->expires_at);
        } else {
            $loginResponse = $this->login();
            $this->accessToken = new AccessToken($loginResponse['access_token'], $loginResponse['expires_at']);
        }

        $this->client = new Client([
            'verify' => false, // @Todo : Remove that in production
            'base_uri' => self::BASE_URI,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken->getToken(),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * Get the active Guzzle client
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Get user's companyid
     *
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * Set user's companyid
     *
     * @param int $companyId
     */
    public function setCompanyId(int $companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Check if the user is already identified and restarts the process if it is not the case
     *
     * @return void
     * @throws \Exception
     */
    public function checkAuthentication()
    {
        if (!$this->hasValidAccessToken()) {
            $loginResponse = $this->login();
            $this->accessToken = new AccessToken($loginResponse['access_token'], $loginResponse['expires_at']);

            $this->client = new Client([
                'verify' => false, // @Todo : Remove that in production
                'base_uri' => self::BASE_URI,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken->getToken(),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);
        }
    }

    /**
     * Get resources default return type
     *
     * @return string
     */
    public function getDefaultReturnType(): string
    {
        return $this->defaultReturnType;
    }

    /**
     * Set resources default return type to Object
     * @return void
     */
    public function setObjectDefaultReturnType()
    {
        $this->defaultReturnType = 'OBJECT';
    }

    /**
     * Set resources default return type to JSON
     * @return void
     */
    public function setJSONDefaultReturnType()
    {
        $this->defaultReturnType = 'JSON';
    }

    /**
     * Login the user with given public and secret keys
     *
     * @return array login response
     */
    private function login(): array
    {
        try {
            $client = new Client([
                'verify' => false, // @Todo : Remove that in production
                'base_uri' => self::BASE_URI,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);

            $loginResponse = $client->post('api/login', [
                'body' => json_encode([
                    'public_key' => $this->publicKey,
                    'secret_key' => $this->secretKey
                ])
            ]);

            $loginResponse = json_decode($loginResponse->getBody()->getContents(), true);

            if (isset($loginResponse['access_token'])) {
                // Cookie Storage
                setcookie('evoliz_token_' . $this->companyId, json_encode([
                    'access_token' => $loginResponse['access_token'],
                    'expires_at' => $loginResponse['expires_at']
                ]));
            }

        } catch (GuzzleException $exception) {
            $loginResponse = $exception->getMessage();
        }

        return $loginResponse;
    }

    /**
     * Check if there is a valid access token stored in the cookies
     *
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
                && $this->accessToken->getExpiresAt()
                > new \DateTime('now'));
    }

}
