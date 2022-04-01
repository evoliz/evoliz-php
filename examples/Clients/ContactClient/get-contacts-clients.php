<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Clients\ContactClientRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$contactClientRepository = new ContactClientRepository($config);

$contactClients = $contactClientRepository->list();

$contactClientsNextPage = $contactClientRepository->paginate($contactClients, 'next');
$contactClientsPreviousPage = $contactClientRepository->paginate($contactClientsNextPage, 'previous');
$contactClientsLastPage = $contactClientRepository->paginate($contactClientsPreviousPage, 'last');
$contactClientsFirstPage = $contactClientRepository->paginate($contactClientsLastPage, 'first');
$contactClientsPage42 = $contactClientRepository->paginate($contactClientsFirstPage, 'perso', 42);

$contactClient = $contactClientRepository->detail(1); // Get ContactClientResponse resource wth Id 1
