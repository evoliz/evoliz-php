<?php

namespace Evoliz\Client\Response\Sales;

use Evoliz\Client\Mapper\Sales\InvoiceMapper;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class InvoiceResponse extends BaseResponse implements ResponseInterface
{
    /**
     * Transform the model in the RequestBody array
     * @return array RequestBody
     */
    public function toRequestBody(): array
    {
        return InvoiceMapper::mapIntoRequestBody($this);
    }
}
