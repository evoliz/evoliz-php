<?php

namespace Evoliz\Client\Response\Catalog;

use Evoliz\Client\Model\Catalog\Article;
use Evoliz\Client\Response\BaseResponse;
use Evoliz\Client\Response\ResponseInterface;

class ArticleResponse extends BaseResponse implements ResponseInterface
{
    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * Build Article from ArticleResponse
     * @return Article
     */
    public function createFromResponse(): Article
    {
        return new Article((array) $this);
    }
}
