<?php

namespace Evoliz\Client\Response\Invoice;

class AnalyticResponse
{
    /**
     * @var integer Analytic axis id
     */
    public $id;

    /**
     * @var string Analytic axis code identifier
     */
    public $code;

    /**
     * @var string Analytic axis label
     */
    public $label;

    /**
     * @var boolean Determines if the analytic axis is active
     */
    public $enabled;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->label = $data['label'] ?? null;
        $this->enabled = $data['enabled'] ?? null;
    }
}
