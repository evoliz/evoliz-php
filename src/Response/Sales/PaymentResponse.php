<?php

namespace Evoliz\Client\Response\Sales;

use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class PaymentResponse extends BaseResponse implements ResponseInterface
{
    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public function createFromResponse()
    {
        // TODO: Implement createFromResponse() method.
    }
}
