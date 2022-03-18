<?php

namespace Evoliz\Client;

abstract class EvolizHelper
{
    static function is_json($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
    }
}
