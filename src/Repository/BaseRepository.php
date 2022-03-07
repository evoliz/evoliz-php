<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use GuzzleHttp\Client;

abstract class BaseRepository
{

    /**
     * @var Client Guzzle active client
     */
    protected $client;

    /**
     * @var string Resources default return type
     * Possible values = 'OBJECT' or 'JSON'
     */
    protected $defaultReturnType;
    /**
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        $config->authenticate();
        $this->client = $config->getClient();
        $this->defaultReturnType = $config->getDefaultReturnType();
    }
}
