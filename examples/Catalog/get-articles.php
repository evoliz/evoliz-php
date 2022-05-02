<?php

use Evoliz\Client\Config;
use Evoliz\Client\Repository\Catalog\ArticleRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$articleRepository = new ArticleRepository($config);

$articles = $articleRepository->list();

$articlePages[] = $articles;

while ($articles = $articleRepository->nextPage($articles)) {
    $articlePages[] = $articles;
}

$articlesPreviousPage = $articleRepository->previousPage($articles);
$articlesLastPage = $articleRepository->lastPage($articlesPreviousPage);
$articlesFirstPage = $articleRepository->firstPage($articlesLastPage);
$articlePage42 = $articleRepository->page($articlesFirstPage, 42);

$article = $articleRepository->detail(1); // Get ArticleResponse resource wth Id 1
