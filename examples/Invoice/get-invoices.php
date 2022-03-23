<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Sales\InvoiceRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$invoiceRepository = new InvoiceRepository($config);

$invoices = $invoiceRepository->list();

$invoicesNextPage = $invoiceRepository->nextPage($invoices);
$invoicesPreviousPage = $invoiceRepository->previousPage($invoicesNextPage);
$invoicesLastPage = $invoiceRepository->lastPage($invoicesPreviousPage);
$invoicesFirstPage = $invoiceRepository->firstPage($invoicesLastPage);

$invoice = $invoiceRepository->detail(1); // Get InvoiceResponse resource wth Id 1
