<?php

namespace Evoliz\Client\Response\Clients;

use Evoliz\Client\Model\Clients\Client\Client;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class ClientResponse extends BaseResponse implements ResponseInterface
{
    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * Build Client from ClientResponse
     * @return Client
     */
    public function createFromResponse(): Client
    {
        return new Client((array) $this);
    }
}
