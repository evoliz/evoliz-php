<?php

namespace Evoliz\Client\Repository\Sales;

use Evoliz\Client\Config;
use Evoliz\Client\Model\Sales\Invoice;
use Evoliz\Client\Repository\BaseRepository;

class InvoiceRepository extends BaseRepository
{
    /**
     * @param Config $config
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config, Invoice::class);
    }
}
