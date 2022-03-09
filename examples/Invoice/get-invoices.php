<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\InvoiceRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');

$invoiceRepository = new InvoiceRepository($config);
$invoices = $invoiceRepository->list();
$invoice = $invoiceRepository->detail(1); // Get Invoice resource with Id 1
