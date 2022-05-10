<?php

namespace Evoliz\Client\Repository\Sales;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\HttpClient;
use Evoliz\Client\Repository\BaseRepository;
use Evoliz\Client\Response\Sales\InvoiceResponse;
use Evoliz\Client\Response\Sales\PaymentResponse;

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
        $response = HttpClient::getInstance()
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

    /**
     * Create a payment on the given invoice
     *
     * @param int $invoiceid Invoice to pay
     * @param string $label Label of the payment
     * @param int $paytypeid PaytypeID of the payment
     * @param float $amount Amount of the payment
     * @param \DateTime|null $paydate Paydate of the payment
     * @param string|null $comment Comment on the payment
     *
     * @return PaymentResponse|string
     *
     * @throws ResourceException
     */
    public function pay(int $invoiceid, string $label, int $paytypeid, float $amount,  \DateTime $paydate = null, string $comment = null)
    {
        $requestBody = [
            'label' => $label,
            'paytypeid' => $paytypeid,
            'amount' => $amount,
        ];

        if ($paydate === null) {
            $paydate = new \DateTime('now');
        }
        $requestBody['paydate'] = $paydate->format('Y-m-d');

        if ($comment) {
            $requestBody['comment'] = $comment;
        }

        $response = HttpClient::getInstance()
            ->post($this->baseEndpoint . '/' . $invoiceid . '/payments', [
                'body' => json_encode($requestBody)
            ]);


        $responseContent = $response->getBody()->getContents();

        $decodedContent = json_decode($responseContent, true);

        $this->handleError($decodedContent, $response->getStatusCode());

        if ($this->config->getDefaultReturnType() === 'OBJECT') {
            return new PaymentResponse($decodedContent);
        } else {
            return $responseContent;
        }
    }
}
