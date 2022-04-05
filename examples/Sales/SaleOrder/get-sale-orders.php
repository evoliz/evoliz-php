<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Sales\SaleOrderRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$saleOrderRepository = new SaleOrderRepository($config);

$saleOrders = $saleOrderRepository->list();

// @Todo : Handle upcoming changements on pagination
$saleOrdersNextPage = $saleOrderRepository->paginate($saleOrders, 'next');
$saleOrdersPreviousPage = $saleOrderRepository->paginate($saleOrdersNextPage, 'previous');
$saleOrdersLastPage = $saleOrderRepository->paginate($saleOrdersPreviousPage, 'last');
$saleOrdersFirstPage = $saleOrderRepository->paginate($saleOrdersLastPage, 'first');
$saleOrdersPage42 = $saleOrderRepository->paginate($saleOrdersFirstPage, 'perso', 42);

$saleOrder = $saleOrderRepository->detail(1); // Get InvoiceResponse resource wth Id 1
