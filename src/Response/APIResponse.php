<?php

namespace Evoliz\Client\Response;

class APIResponse
{
    /**
     * Construction of the API Response Resource
     *
     * @param array $data  Requested resource array
     * @param array $links Links to the different pages for pagination
     * @param array $meta  Various meta tags
     */
    public function __construct(public array $data, public array $links, public array $meta)
    {
    }
}
