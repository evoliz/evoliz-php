<?php

namespace Evoliz\Client;

use GuzzleHttp\Client;

class HttpClient extends Client
{
    const BASE_URI = "https://www.evoliz.io/";

    /**
     * @var HttpClient
     */
    private static $instance = null;

    /**
     * Get the current HttpClient instance
     *
     * @param  array $config If set, a new client will be created with new config
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new static(self::defaultConfig());
        }

        return self::$instance;
    }

    /**
     * Set the current HttpClient instance
     *
     * @param  array $config add or override default config
     */
    public static function setInstance(array $config = [])
    {
        $currentConfig = self::$instance !== null ? self::$instance->getConfig() : self::defaultConfig();

        self::$instance = new static(array_merge($currentConfig, $config));
    }

    private static function defaultConfig(): array
    {
        return [
            'base_uri' => self::BASE_URI,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'evoliz-php/' . Config::VERSION,
            ],
            'verify' => true,
        ];
    }
}
