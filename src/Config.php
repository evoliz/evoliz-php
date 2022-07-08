<?php

namespace Evoliz\Client;

use Evoliz\Client\Exception\ConfigException;

class Config
{
    const VERSION = "dev";
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
     * @param int $companyId User's companyid
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
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * Get user's secret key
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * Get user's public key
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * Get the Access Token linked to the connection
     * @return AccessToken|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the access token
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
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
     * Check if there is a valid access token stored in the config
     */
    public function hasValidAccessToken(): bool
    {
        return isset($this->accessToken)
            && !$this->accessToken->isExpired();
    }
}
