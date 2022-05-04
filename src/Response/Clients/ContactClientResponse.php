<?php

namespace Evoliz\Client\Response\Clients;

use Evoliz\Client\Model\Clients\ContactClient;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class ContactClientResponse extends BaseResponse implements ResponseInterface
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
