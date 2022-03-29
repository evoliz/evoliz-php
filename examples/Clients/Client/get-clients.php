<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Clients\ClientRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
$config->setDefaultReturnType($config::OBJECT_RETURN_TYPE);

$clientRepository = new ClientRepository($config);
$clients = $clientRepository->list();
$client = $clientRepository->detail(1); // Get ClientResponse resource wth Id 1