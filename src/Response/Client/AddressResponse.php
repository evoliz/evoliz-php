<?php

namespace Evoliz\Client\Response\Client;

class AddressResponse
{
    /**
     * @var string Address line 1
     */
    public $addr;

    /**
     * @var string Address line 2
     */
    public $addr2;

    /**
     * @var string Postcode
     */
    public $postcode;

    /**
     * @var string Town
     */
    public $town;

    /**
     * @var CountryResponse Country informations
     */
    public $country;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->addr = $data['addr'] ?? null;
        $this->addr2 = $data['addr2'] ?? null;
        $this->postcode = $data['postcode'] ?? null;
        $this->town = $data['town'] ?? null;
        $this->country = isset($data['country']) ? new CountryResponse($data['country']) : null;
    }
}
