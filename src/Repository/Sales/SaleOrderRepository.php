<?php

namespace Evoliz\Client\Repository\Sales;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\HttpClient;
use Evoliz\Client\Repository\BaseRepository;
use Evoliz\Client\Response\Sales\InvoiceResponse;
use Evoliz\Client\Response\Sales\SaleOrderResponse;

class SaleOrderRepository extends BaseRepository
{
    /**
     * Set up the different parameters for the API requests
     * @param Config $config Configuration for API usage
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config, 'sale-orders', SaleOrderResponse::class);
    }

    /**
     * Invoice a sale order
     *
     * @param int $orderid The sale order id to invoice
     *
     * @return InvoiceResponse|string
     *
     * @throws ResourceException
     */
    public function invoice(int $orderid, bool $save = false)
    {
        $response = HttpClient::getInstance()
            ->post($this->baseEndpoint . '/' . $orderid . '/invoice');

        $responseContent = $response->getBody()->getContents();

        $decodedContent = json_decode($responseContent, true);

        $this->handleError($decodedContent, $response->getStatusCode());

        if ($save) {
            return (new InvoiceRepository($this->config))->save($decodedContent['invoiceid']);
        }

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new InvoiceResponse($decodedContent);
        } else {
            return $responseContent;
        }
    }
}
