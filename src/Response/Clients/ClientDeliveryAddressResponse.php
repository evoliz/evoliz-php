<?php

namespace Evoliz\Client\Response\Clients;

use Evoliz\Client\Model\Clients\Client\ClientDeliveryAddress;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class ClientDeliveryAddressResponse extends BaseResponse implements ResponseInterface
{
    public function createFromResponse(): ClientDeliveryAddress
    {
        return new ClientDeliveryAddress((array) $this);
    }
}
