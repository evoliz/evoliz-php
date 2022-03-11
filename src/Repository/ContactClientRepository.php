<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\Model\ContactClient;

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
