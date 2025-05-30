<?php

namespace Evoliz\Client\Model\Clients\Client;

use Evoliz\Client\Model\BaseModel;

class Address extends BaseModel
{
    /**
     * @var string Postcode
     */
    public $postcode;

    /**
     * @var string Town
     */
    public $town;

    /**
     * @var string Address ISO2
     */
    public $iso2;

    /**
     * @var string Address line 1
     */
    public $addr;

    /**
     * @var string Address line 2
     */
    public $addr2;

    /**
     * @param array $data Array to build the object
     */
    public function __construct(array $data)
    {
        $this->iso2 = $this->extractIso2($data);

        $this->postcode = $data['postcode'] ?? null;
        $this->town = $data['town'] ?? null;
        $this->addr = $data['addr'] ?? null;
        $this->addr2 = $data['addr2'] ?? null;
    }

    /**
     * Extract the iso2 field with the correct information
     *
     * @param  array $data Array to build the object
     * @return string|null
     */
    private function extractIso2(array $data)
    {
        if (isset($data['country'])) {
            $iso2 = $data['country']->iso2;
        } elseif (isset($data['iso2'])) {
            $iso2 = $data['iso2'];
        }

        return $iso2 ?? null;
    }
}
