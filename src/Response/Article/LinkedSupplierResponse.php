<?php

namespace Evoliz\Client\Response\Article;

class LinkedSupplierResponse
{
    /**
     * @var integer Linked supplier Id
     */
    public $supplierid;

    /**
     * @var string Supplier Code identifier
     */
    public $code;

    /**
     * @var string Supplier name
     */
    public $name;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->supplierid = $data['supplierid'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->name = $data['name'] ?? null;
    }
}
