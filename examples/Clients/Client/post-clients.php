<?php

use Evoliz\Client\Config;
use Evoliz\Client\Model\Clients\Client\Client;
use Evoliz\Client\Repository\Clients\ClientRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
$config->setDefaultReturnType($config::OBJECT_RETURN_TYPE);

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
