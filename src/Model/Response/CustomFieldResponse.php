<?php

namespace Evoliz\Client\Model\Response;

class CustomFieldResponse
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $value;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->label = $data['label'] ?? null;
        $this->value = $data['value'] ?? null;
    }

}
