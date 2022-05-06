<?php

namespace Evoliz\Client\Response\Clients;

use Evoliz\Client\Model\Clients\Client\Client;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class ClientResponse extends BaseResponse implements ResponseInterface
{
    /**
     * Build Client from ClientResponse
     * @return Client
     */
    public function createFromResponse(): Client
    {
        return new Client((array) $this);
    }
}
