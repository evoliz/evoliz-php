<?php

namespace Evoliz\Client\Response\Invoice;

class SaleClassificationResponse
{
    /**
     * @var integer Classification id
     */
    public $id;

    /**
     * @var string Classification code
     */
    public $code;

    /**
     * @var string Classification label
     */
    public $label;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->label = $data['label'] ?? null;
    }

}
