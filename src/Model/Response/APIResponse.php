<?php

namespace Evoliz\Client\Model\Response;

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
     * @param $data
     * @param $links
     * @param $meta
     */
    public function __construct($data, $links, $meta)
    {
        $this->data = $data;
        $this->links = $links;
        $this->meta = $meta;
    }
}
