<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Sales\SaleOrderRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');

$saleOrderRepository = new SaleOrderRepository($config);

$saleOrders = $saleOrderRepository->list();

// Return a draft invoice
$draftInvoice = $saleOrderRepository->invoice($saleOrders->data[0]->orderid);

// The invoice method takes a 2nd argument to precise whether to save the invoice or to keep it as draft
$savedInvoice = $saleOrderRepository->invoice($saleOrders->data[0]->orderid, true);
