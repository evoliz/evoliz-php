<?php

namespace Evoliz\Client\Repository\Clients;

use Evoliz\Client\Config;
use Evoliz\Client\Model\Clients\ContactClient;
use Evoliz\Client\Repository\BaseRepository;
use Evoliz\Client\Response\ContactClient\ContactClientResponse;

class ContactClientRepository extends BaseRepository
{
    /**
     * Set up the different parameters for the API requests
     * @param Config $config Configuration for API usage
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config, 'contacts-clients', ContactClientResponse::class);
    }
}
