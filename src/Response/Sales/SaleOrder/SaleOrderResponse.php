<?php

namespace Evoliz\Client\Response\Sales\SaleOrder;

use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class SaleOrderResponse extends BaseResponse implements ResponseInterface
{
    /**
     * @param array $data Response array to build the object
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }

    public function createFromResponse()
    {
        // TODO: Implement createFromResponse() method.
    }
}
