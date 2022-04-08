<?php

namespace Evoliz\Client\Response\Clients;

use Evoliz\Client\Model\Clients\ContactClient;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class ContactClientResponse extends BaseResponse implements ResponseInterface
{
    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * Build ContactClient from ContactClientResponse
     * @return ContactClient
     */
    public function createFromResponse(): ContactClient
    {
        return new ContactClient((array) $this);
    }
}
