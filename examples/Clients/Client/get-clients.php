<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Clients\ClientRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$clientRepository = new ClientRepository($config);

$clients = $clientRepository->list();

$clientsNextPage = $clientRepository->nextPage($clients);
$clientsPreviousPage = $clientRepository->previousPage($clientsNextPage);
$clientsLastPage = $clientRepository->lastPage($clientsPreviousPage);
$clientsFirstPage = $clientRepository->firstPage($clientsLastPage);
$clientsPage42 = $clientRepository->page($clientsLastPage, 42);

$client = $clientRepository->detail(1); // Get ClientResponse resource wth Id 1
