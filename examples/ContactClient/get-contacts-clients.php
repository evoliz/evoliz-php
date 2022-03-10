<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\ContactClientRepository;

require 'vendor/autoload.php';

$config = new Config(EVOLIZ_COMPANYID, EVOLIZ_PUBLIC_KEY, EVOLIZ_SECRET_KEY);
$config->setDefaultReturnType($config::OBJECT_RETURN_TYPE);

$contactClientRepository = new ContactClientRepository($config);
$contactClients = $contactClientRepository->list();
$contactClient = $contactClientRepository->detail(1); // Get ContactClientResponse resource wth Id 1


