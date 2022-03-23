<?php

namespace Evoliz\Client\Repository\Sales;

use Evoliz\Client\Config;
use Evoliz\Client\Model\Sales\Invoice;
use Evoliz\Client\Repository\BaseRepository;
use Evoliz\Client\Response\Invoice\InvoiceResponse;

class InvoiceRepository extends BaseRepository
{
    /**
     * Setup the different parameters for the API requests
     * @param Config $config Configuration for API usage
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config, Invoice::class, 'invoices', InvoiceResponse::class);
    }
}
