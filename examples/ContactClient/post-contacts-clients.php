<?php

use Evoliz\Client\Config;
use Evoliz\Client\Model\ContactClient;
use Evoliz\Client\Repository\ContactClientRepository;

require 'vendor/autoload.php';

$config = new Config(EVOLIZ_COMPANYID, EVOLIZ_PUBLIC_KEY, EVOLIZ_SECRET_KEY);
$config->setDefaultReturnType($config::OBJECT_RETURN_TYPE);

$contactClientRepository = new ContactClientRepository($config);
$newContactClient = $contactClientRepository->create(new ContactClient([
    'clientid' => 1,
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'contact@johndoe.com',
]));