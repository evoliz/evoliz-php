<?php

namespace Evoliz\Client\Repository\Sales;

use Evoliz\Client\Config;
use Evoliz\Client\Repository\BaseRepository;
use Evoliz\Client\Response\Sales\InvoiceResponse;

class InvoiceRepository extends BaseRepository
{
    /**
     * Set up the different parameters for the API requests
     * @param Config $config Configuration for API usage
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config, 'invoices', InvoiceResponse::class);
    }
}
