<?php

namespace Evoliz\Client\Repository\Sales;

use Evoliz\Client\Config;
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
     */
    public function invoice(int $orderid, bool $save = false)
    {
        $response = $this->config->getClient()
            ->post($this->baseEndpoint . '/' . $orderid . '/invoice', []);

        $responseContent = $response->getBody()->getContents();

        $decodedContent = json_decode($responseContent, true);

        $this->handleError($decodedContent, $response->getStatusCode());

        // @TODO : save if $save is true
        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new InvoiceResponse($decodedContent);
        } else {
            return $responseContent;
        }
    }
}
