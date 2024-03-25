<?php

namespace Evoliz\Client;

use Evoliz\Client\Exception\ConfigException;

class Config
{
    const VERSION = "v0.7.0";
    const OBJECT_RETURN_TYPE = "OBJECT";
    const JSON_RETURN_TYPE = "JSON";

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
     *
     * @param int    $companyId User's companyid
     * @param string $publicKey User's public key given in the app
     *
     * @throws \Exception|ConfigException
     */
    public function __construct(int $companyId, string $publicKey, string $secretKey)
    {
        $this->companyId = $companyId;
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
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
     * Get the Access Token linked to the connection
     *
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * Authenticate the user
     *
     * @return Config
     * @throws \Exception|ConfigException
     */
    public function authenticate(): Config
    {
        if ($this->hasValidAccessToken()) {
            if ($this->hasValidCookieAccessToken() && !$this->hasValidConfigAccessToken()) {
                $decodedToken = json_decode($_COOKIE['evoliz_token_' . $this->companyId]);
                $this->accessToken = new AccessToken($decodedToken->access_token, $decodedToken->expires_at);
            }
        } else {
            $this->accessToken = $this->login();
        }

        HttpClient::setInstance(
            [],
            ['Authorization' => 'Bearer ' . $this->accessToken->getToken()]
        );

        return $this;
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
     * Set resources default return type
     *
     * @param  string $returnType Expected type of return (OBJECT or JSON)
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
     *
     * @return AccessToken
     * @throws ConfigException|\Exception
     */
    private function login(): AccessToken
    {
        $loginResponse = HttpClient::getInstance()->post(
            'api/login',
            [
                'body' => json_encode([
                    'public_key' => $this->publicKey,
                    'secret_key' => $this->secretKey
                ])
            ]
        );

        $responseBody = json_decode($loginResponse->getBody()->getContents());

        if ($loginResponse->getStatusCode() !== 200) {
            $errorMessage = $responseBody->error . ' : ' . $responseBody->message;
            throw new ConfigException($errorMessage, $loginResponse->getStatusCode());
        }

        if (isset($responseBody->access_token)) {
            // Cookie Storage
            setcookie(
                'evoliz_token_' . $this->companyId,
                json_encode(
                    [
                        'access_token' => $responseBody->access_token,
                        'expires_at' => $responseBody->expires_at
                    ]
                )
            );

            $accessToken = new AccessToken($responseBody->access_token, $responseBody->expires_at);
        } else {
            throw new ConfigException('The access token has not been recovered', 422);
        }

        return $accessToken;
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
     * Check if there is a valid access token stored in the config
     */
    private function hasValidConfigAccessToken(): bool
    {
        return isset($this->accessToken)
            && !$this->accessToken->isExpired();
    }

    /**
     * Check if there is a valid access token stored in the cookies or the config
     *
     * @throws \Exception
     */
    private function hasValidAccessToken(): bool
    {
        return $this->hasValidCookieAccessToken()
            || $this->hasValidConfigAccessToken();
    }


    /**
     * Check if the company id is valid
     *
     * @return bool
     */
    public function hasValidCompanyId(): bool
    {
        $companyResponse = HttpClient::getInstance()
            ->get('api/companies/' . $this->companyId);

        return $companyResponse->getStatusCode() === 200;
    }
}
