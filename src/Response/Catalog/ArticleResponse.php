<?php

namespace Evoliz\Client\Response\Catalog;

use Evoliz\Client\Model\Catalog\Article;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class ArticleResponse extends BaseResponse implements ResponseInterface
{
    /**
     * Build Article from ArticleResponse
     *
     * @return Article
     */
    public function createFromResponse(): Article
    {
        return new Article((array) $this);
    }
}
