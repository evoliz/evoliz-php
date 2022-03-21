<?php

namespace Evoliz\Client\Response;

class APIResponse
{
    /**
     * @var array of Requested Resource
     */
    public $data;

    /**
     * @var array of Links
     */
    public $links;

    /**
     * @var array of Meta tags
     */
    public $meta;

    /**
     * Construction of the API Response Resource
     * @param array $data Requested resource array
     * @param array $links Links to the different pages for pagination
     * @param array $meta Various meta tags
     */
    public function __construct(array $data, array $links, array $meta)
    {
        $this->data = $data;
        $this->links = $links;
        $this->meta = $meta;
    }
}
