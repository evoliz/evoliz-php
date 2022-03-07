<?php

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;

require 'vendor/autoload.php';

// Manage your credentials storage as you wish.
const EVOLIZ_COMPANYID = 'YOUR_COMPANYID'; // Integer
const EVOLIZ_PUBLIC_KEY = 'YOUR_PUBLIC_KEY'; // String
const EVOLIZ_SECRET_KEY = 'YOUR_SECRET_KEY'; // String

$config = new Config(EVOLIZ_COMPANYID, EVOLIZ_PUBLIC_KEY, EVOLIZ_SECRET_KEY);

try {
    $config->setDefaultReturnType($config::JSON_RETURN_TYPE);
} catch (ConfigException $exception) {
    exit($exception->getMessage());
}
