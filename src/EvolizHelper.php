<?php

namespace Evoliz\Client;

abstract class EvolizHelper
{
    /**
     * Check if the given parameter is of JSON type
     * @param mixed $parameter Value to check
     * @return bool
     */
    static function is_json($parameter): bool
    {
        return is_string($parameter) && is_array(json_decode($parameter, true)) && (json_last_error() == JSON_ERROR_NONE);
    }
}
