<?php

namespace Evoliz\Client\Repository\Clients;

use Evoliz\Client\Config;
use Evoliz\Client\Model\Clients\Client\Client;
use Evoliz\Client\Repository\BaseRepository;

class ClientRepository extends BaseRepository
{
    /**
     * Setup the different parameters for the API requests
     * @param Config $config Configuration for API usage
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config, Client::class);
    }
}
