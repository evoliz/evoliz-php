<?php

use Evoliz\Client\Config;
use Evoliz\Client\Model\Clients\ContactClient;
use Evoliz\Client\Repository\Clients\ContactClientRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$contactClientRepository = new ContactClientRepository($config);
$newContactClient = $contactClientRepository->create(new ContactClient([
    'clientid' => 1,
    'lastname' => 'Doe',
    'email' => 'contact@johndoe.com',
]));
