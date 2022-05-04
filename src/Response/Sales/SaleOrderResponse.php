<?php

namespace Evoliz\Client\Response\Sales;

use Evoliz\Client\Model\Sales\SaleOrder;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class SaleOrderResponse extends BaseResponse implements ResponseInterface
{
    /**
     * Transform the model in the RequestBody array
     * @return array RequestBody
     */
    public function toRequestBody(): array
    {
        // TODO: Implement toRequestBody() method.
    }
}
