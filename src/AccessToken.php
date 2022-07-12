<?php

namespace Evoliz\Client;

class AccessToken
{
    /**
     * @var string Token that should be used for authentication as a Bearer Authorization Token
     */
    private $token;

    /**
     * @var \DateTime Session's token duration. Set by default to 20 minutes
     */
    private $expires_at;

    /**
     * Setup the Access Token parameters
     *
     * @param  string $token      Token that should be used for authentication as a Bearer Authorization Token
     * @param  string $expires_at Session's token duration. Set by default to 20 minutes
     * @throws \Exception
     */
    public function __construct(string $token, string $expires_at)
    {
        $this->token = $token;
        $this->expires_at = new \DateTime($expires_at);
    }

    /**
     * Get token value
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Get token expiration date
     *
     * @return \DateTime
     */
    public function getExpiresAt(): \DateTime
    {
        return $this->expires_at;
    }

    /**
     * Check if the token is expired or not
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at < new \DateTime('now');
    }
}
