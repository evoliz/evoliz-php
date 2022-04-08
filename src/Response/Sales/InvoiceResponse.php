<?php

namespace Evoliz\Client\Response\Sales;

use Evoliz\Client\Model\Sales\Invoice;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class InvoiceResponse extends BaseResponse implements ResponseInterface
{
    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * Build Invoice from InvoiceResponse
     * @return Invoice
     */
    public function createFromResponse(): Invoice
    {
        return new Invoice((array) $this);
    }
}
