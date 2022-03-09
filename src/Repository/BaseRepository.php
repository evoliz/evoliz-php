<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;

abstract class BaseRepository
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        $this->config = $config->authenticate();
    }
}
