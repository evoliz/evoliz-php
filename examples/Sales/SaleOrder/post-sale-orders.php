<?php

use Evoliz\Client\Config;
use Evoliz\Client\Model\Item;
use Evoliz\Client\Model\Sales\SaleOrder;
use Evoliz\Client\Repository\Sales\SaleOrderRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$saleOrderRepository = new SaleOrderRepository($config);
$newSaleOrder = $saleOrderRepository->create(new SaleOrder([
    'external_document_number' => 'evz42',
    'documentdate' => '2022-03-14',
    'clientid' => 1,
    'term' => [
        'paytermid' => 1,
    ],
    'items' => [
        new Item([
            'articleid' => 1,
        ]),
        new Item([
            'designation' => 'Banana',
            'quantity' => 42,
            'unit_price_vat_exclude' => 1
        ])
    ]
]));
