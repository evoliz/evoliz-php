<?php

namespace Evoliz\Client\Services;

use Evoliz\Client\AccessToken;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\AuthenticationException;
use Evoliz\Client\HttpClient;

class AuthenticationService
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Authenticate to the Evoliz API
     *
     * @throws AuthenticationException
     */
    public function authenticate(): self
    {
        if ($this->config->hasValidAccessToken()) {
            HttpClient::setAuthorizationHeader($this->config->getAccessToken()->getToken());

            return $this;
        }

        $cookieToken = $this->getValidCookieAccessToken();

        if ($cookieToken) {
            $this->config->setAccessToken($cookieToken);
            HttpClient::setAuthorizationHeader($this->config->getAccessToken()->getToken());

            return $this;
        }

        $this->config->setAccessToken($this->requestToken());
        HttpClient::setAuthorizationHeader($this->config->getAccessToken()->getToken());

        return $this;
    }

    /**
     * Request a new token from the Evoliz API
     *
     * @return AccessToken
     *
     * @throws AuthenticationException|\Exception
     */
    private function requestToken(): AccessToken
    {
        $loginResponse = HttpClient::getInstance()->post('api/login', [
            'body' => json_encode([
                'public_key' => $this->config->getPublicKey(),
                'secret_key' => $this->config->getSecretKey(),
            ]),
        ]);

        $responseBody = json_decode($loginResponse->getBody()->getContents());

        if ($loginResponse->getStatusCode() !== 200) {
            $errorMessage = $responseBody->error . ' : ' . $responseBody->message;
            throw new AuthenticationException($errorMessage, $loginResponse->getStatusCode());
        }

        if (isset($responseBody->access_token)) {
            // Cookie Storage
            setcookie('evoliz_token_' . $this->config->getCompanyId(), json_encode([
                'access_token' => $responseBody->access_token,
                'expires_at' => $responseBody->expires_at,
            ]));

            $accessToken = new AccessToken($responseBody->access_token, $responseBody->expires_at);
        } else {
            throw new AuthenticationException('The access token has not been recovered', 422);
        }

        return $accessToken;
    }

    /**
     * Get access token from cookies if its still valid
     *
     * @return AccessToken|false
     */
    public function getValidCookieAccessToken()
    {
        $cookieAccessToken = $_COOKIE['evoliz_token_' . $this->config->getCompanyId()] ?? null;

        if ($cookieAccessToken) {
            $decodedToken = json_decode($cookieAccessToken);

            $accessToken = new AccessToken($decodedToken->access_token, $decodedToken->expires_at);
            return $accessToken->isExpired() ? false : $accessToken;
        }

        return false;
    }
}
