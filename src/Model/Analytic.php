<?php

namespace Evoliz\Client\Model;

class Analytic
{
    /**
     * @var integer Analytical axis id
     */
    public $id;

    /**
     * @var string Analytical axis code identifier
     */
    public $code;

    /**
     * @var string Analytical axis label
     */
    public $label;

    /**
     * @var boolean
     */
    public $enabled;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->label = $data['label'] ?? null;
        $this->enabled = $data['enabled'] ?? null;
    }
}
