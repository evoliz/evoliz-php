<?php

use Evoliz\Client\Config;
use Evoliz\Client\Model\Clients\Client\Client;
use Evoliz\Client\Repository\Clients\ClientRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$clientRepository = new ClientRepository($config);
$newClient = $clientRepository->create(new Client([
    'name' => 'Doe',
    'type' => 'Particulier',
    'address' => [
        'postcode' => '83130',
        'town' => 'La Garde',
        'iso2' => 'FR'
    ]
]));
