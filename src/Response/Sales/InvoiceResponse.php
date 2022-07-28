<?php

namespace Evoliz\Client\Response\Sales;

use Evoliz\Client\Model\Sales\Invoice;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class InvoiceResponse extends BaseResponse implements ResponseInterface
{
    /**
     * Build Invoice from InvoiceResponse
     *
     * @return Invoice
     */
    public function createFromResponse(): Invoice
    {
        return new Invoice((array) $this);
    }
}
