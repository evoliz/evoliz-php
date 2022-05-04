<?php

namespace Evoliz\Client;

use GuzzleHttp\Client;

class HttpClient extends Client
{
    const BASE_URI = "https://www.evoliz.io/";

    /**
     * @var HttpClient The SDK global HttpClient instance
     */
    private static $instance = null;

    /**
     * Get the current HttpClient instance
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
     * @param array $config Add or override default config
     * @param array $headers Add or override default headers
     */
    public static function setInstance(array $config = [], array $headers = [])
    {
        $currentConfig = self::$instance !== null ? self::$instance->getConfig() : self::defaultConfig();

        $currentConfig['headers'] = array_merge($headers, $currentConfig['headers']);

        self::$instance = new static(array_merge($currentConfig, $config));
    }

    /**
     * Default configuration for guzzle client
     */
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
