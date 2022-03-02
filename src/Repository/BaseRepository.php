<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;

abstract class BaseRepository
{
    /**
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        $config->authenticate();
    }
}
