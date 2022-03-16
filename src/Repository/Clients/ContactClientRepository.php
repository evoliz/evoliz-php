<?php

namespace Evoliz\Client\Repository\Clients;

use Evoliz\Client\Config;
use Evoliz\Client\Model\Clients\ContactClient;
use Evoliz\Client\Repository\BaseRepository;

class ContactClientRepository extends BaseRepository
{
    /**
     * @param Config $config
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config, ContactClient::class);
    }
}
