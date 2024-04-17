<?php

namespace Evoliz\Client\Model\Clients\Client;

use Evoliz\Client\Model\BaseModel;

class ClientDeliveryAddress extends BaseModel
{
    /**
     * @var string
     */
    public $clientid;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $code;

    /**
     * @var bool
     */
    public $favorite;

    /**
     * @var string
     */
    public $addr;

    /**
     * @var string
     */
    public $addr2;

    /**
     * @var string
     */
    public $addr3;

    /**
     * @var string
     */
    public $postcode;

    /**
     * @var string
     */
    public $town;

    /**
     * @var string
     */
    public $iso2;

    /**
     * @var string
     */
    public $siret;

    public function __construct(array $data)
    {
        $this->clientid = $data['clientid'] ?? null;

        $this->name = $data['name'] ?? null;
        $this->type = 'delivery';
        $this->code = $data['code'] ?? null;

        $this->favorite = $data['favorite'] ?? false;

        $this->addr = $data['addr'] ?? null;
        $this->addr2 = $data['addr2'] ?? null;
        $this->addr3 = $data['addr3'] ?? null;

        $this->postcode = $data['postcode'] ?? null;
        $this->town = $data['town'] ?? null;
        $this->iso2 = $this->extractIso2($data);

        $this->siret = $data['siret'] ?? null;
    }

    /**
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
