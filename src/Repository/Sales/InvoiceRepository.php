<?php

namespace Evoliz\Client\Repository\Sales;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ResourceException;
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

    /**
     * Save a draft invoice
     *
     * @return InvoiceResponse|string
     *
     * @throws ResourceException
     */
    public function save(int $invoiceid)
    {
        $response = $this->config->getClient()
            ->post($this->baseEndpoint . '/' . $invoiceid . '/create');

        $responseContent = $response->getBody()->getContents();

        $decodedContent = json_decode($responseContent, true);

        $this->handleError($decodedContent, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new InvoiceResponse($decodedContent);
        } else {
            return $responseContent;
        }
    }
}
