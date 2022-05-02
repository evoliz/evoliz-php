<?php

namespace Evoliz\Client\Response\Client;

class CountryResponse
{
    /**
     * @var string Country name
     */
    public $label;

    /**
     * @var string Country ISO2 code
     */
    public $iso2;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->label = $data['label'] ?? null;
        $this->iso2 = $data['iso2'] ?? null;
    }


}
