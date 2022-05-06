<?php

namespace Evoliz\Client\Response\Sales;

use Evoliz\Client\Model\Sales\SaleOrder;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class SaleOrderResponse extends BaseResponse implements ResponseInterface
{
    public function createFromResponse(): SaleOrder
    {
        return new SaleOrder((array) $this);
    }
}
