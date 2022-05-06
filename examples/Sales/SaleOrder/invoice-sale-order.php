<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Sales\SaleOrderRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');

$saleOrderRepository = new SaleOrderRepository($config);

$saleOrders = $saleOrderRepository->list();

// Return a draft invoice
$invoice = $saleOrderRepository->invoice($saleOrders->data[0]->orderid);
