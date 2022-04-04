<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Clients\ContactClientRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
$config->setDefaultReturnType($config::OBJECT_RETURN_TYPE);

$contactClientRepository = new ContactClientRepository($config);

$contactClients = $contactClientRepository->list();

$contactClientsNextPage = $contactClientRepository->nextPage($contactClients);
$contactClientsPreviousPage = $contactClientRepository->previousPage($contactClientsNextPage);
$contactClientsLastPage = $contactClientRepository->lastPage($contactClientsPreviousPage);
$contactClientsFirstPage = $contactClientRepository->firstPage($contactClientsLastPage);

$contactClient = $contactClientRepository->detail(1); // Get ContactClientResponse resource wth Id 1
