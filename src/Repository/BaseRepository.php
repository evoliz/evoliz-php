<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;

class BaseRepository
{
    /**
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        $config->checkAuthentication();
    }
}