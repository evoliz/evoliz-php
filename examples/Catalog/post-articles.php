<?php

use Evoliz\Client\Config;
use Evoliz\Client\Model\Catalog\Article;
use Evoliz\Client\Repository\Catalog\ArticleRepository;

require 'vendor/autoload.php';

$config = new Config('EVOLIZ_COMPANYID', 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
// Resources are returned in OBJECT type by default.
// If you want resources to be returned as JSON, set default return type to JSON
// Use $config->setDefaultReturnType($config::JSON_RETURN_TYPE);

$articleRepository = new ArticleRepository($config);
$newArticle = $articleRepository->create(new Article([
    'reference' => 'evz42',
    'designation' => 'Mug banana'
]));
