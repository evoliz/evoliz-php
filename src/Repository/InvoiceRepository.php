<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;

class InvoiceRepository extends BaseRepository
{
    /**
     * @param Config $config
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);
    }
}
