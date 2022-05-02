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

$contactClientPages[] = $contactClients;

while ($contactClients = $contactClientRepository->nextPage()) {
    $contactClientPages[] = $contactClients;
}

$contactClientsPreviousPage = $contactClientRepository->previousPage();
$contactClientsLastPage = $contactClientRepository->lastPage();
$contactClientsFirstPage = $contactClientRepository->firstPage();
$contactClientsPage42 = $contactClientRepository->page(42);

$contactClient = $contactClientRepository->detail(1); // Get ContactClientResponse resource wth Id 1
