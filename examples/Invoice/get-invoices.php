<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Sales\InvoiceRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
$config->setDefaultReturnType($config::OBJECT_RETURN_TYPE);

$invoiceRepository = new InvoiceRepository($config);
$invoices = $invoiceRepository->list();
$invoice = $invoiceRepository->detail(1); // Get InvoiceResponse resource wth Id 1
