<?php

namespace Evoliz\Client\Response\ContactClient;

class CustomFieldResponse
{
    /**
     * @var string Custom field label
     */
    public $label;

    /**
     * @var string Custom field value
     */
    public $value;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->label = $data['label'] ?? null;
        $this->value = $data['value'] ?? null;
    }

}
