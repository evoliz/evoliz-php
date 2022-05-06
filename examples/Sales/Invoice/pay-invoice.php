<?php

use Evoliz\Client\Config;
use Evoliz\Client\Model\Item;
use Evoliz\Client\Model\Sales\Invoice;
use Evoliz\Client\Repository\Sales\InvoiceRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$invoiceRepository = new InvoiceRepository($config);

$payment = $invoiceRepository
    ->pay(1, '2022-05-01', 'Payment with the SDK', 1, 1, 'Partially pay the invoice');

// If you want to fully pay the invoice you can do
$invoice = $invoice = $invoiceRepository->detail(1);
$payment = $invoiceRepository->pay($invoice->invoiceid,'2022-05-01', 'Payment with the SDK', 1, $invoice->total->net_to_pay, 'Full payment of the invoice');
