<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\Model\Invoice;

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

    /**
     * Return a list of invoices visible by the current User, according to visibility restriction set in user profile
     * @param array $query
     * @return array|string Invoices list in the expected format (OBJECT or JSON)
     */
    public function list(array $query = [])
    {
        $response = $this->config->getClient()->get('api/v1/invoices', [
            'query' => $query
        ]);

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            $invoices = [];
            foreach (json_decode($response->getBody()->getContents(), true)['data'] as $invoiceData) {
                $invoices[] = new Invoice($invoiceData);
            }

            return $invoices;
        } else {
            return $response->getBody()->getContents();
        }
    }

    /**
     * Return an invoice by its speficied id
     * @param int $invoiceid
     * @return Invoice|string Invoice in the expected format (OBJECT or JSON)
     */
    public function detail(int $invoiceid)
    {
        $response = $this->config->getClient()->get('api/v1/invoices/' . $invoiceid);

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new Invoice(json_decode($response->getBody()->getContents(), true));
        } else {
            return $response->getBody()->getContents();
        }
    }
}
