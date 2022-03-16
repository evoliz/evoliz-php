<?php

use Evoliz\Client\Config;
use Evoliz\Client\Model\Item;
use Evoliz\Client\Model\Sales\Invoice;
use Evoliz\Client\Repository\Sales\InvoiceRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
$config->setDefaultReturnType($config::OBJECT_RETURN_TYPE);

$invoiceRepository = new InvoiceRepository($config);
$newInvoice = $invoiceRepository->create(new Invoice([
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
            'quantity' => '42',
            'unit_price_vat_exclude' => 1
        ])
    ]
]));
