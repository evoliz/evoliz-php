<?php

namespace Evoliz\Client\Response\Catalog;

use Evoliz\Client\Model\Catalog\Article;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class ArticleResponse extends BaseResponse implements ResponseInterface
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
