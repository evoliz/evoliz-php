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

$articlesNextPage = $articleRepository->paginate($articles, 'next');
$articlesPreviousPage = $articleRepository->paginate($articlesNextPage, 'previous');
$articlesLastPage = $articleRepository->paginate($articlesPreviousPage, 'last');
$articlesFirstPage = $articleRepository->paginate($articlesLastPage, 'first');
$articlesPage42 = $articleRepository->paginate($articlesFirstPage, 'perso', 42);

$article = $articleRepository->detail(1); // Get ArticleResponse resource wth Id 1
