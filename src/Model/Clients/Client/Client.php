<?php

namespace Evoliz\Client\Model\Clients\Client;

use Evoliz\Client\Model\BaseModel;

class Client extends BaseModel
{
    /**
     * @var string Client id
     */
    public $clientid;

    /**
     * @var string Client name
     */
    public $name;

    /**
     * @var string Client type
     * "Particulier", "Professionnel" or "Administration publique"
     */
    public $type;

    /**
     * @var Address Client address informations
     */
    public $address;

    /**
     * @var string Client code, if not filled it will be automatically generated
     */
    public $code;

    /**
     * @var string Client legal form
     */
    public $legalform;

    /**
     * @var string Client activity number
     */
    public $activity_number;

    /**
     * @var string Client immatriculation number
     */
    public $immat_number;

    /**
     * @var BankInformation Client bank informations
     */
    public $bank_information;

    /**
     * @var Address Client delivery address informations
     */
    public $delivery_address;

    /**
     * @var string Client phone number
     */
    public $phone;

    /**
     * @var string Client mobile number
     */
    public $mobile;

    /**
     * @var string Client fax number
     */
    public $fax;

    /**
     * @var string Client website URL
     */
    public $website;

    /**
     * @var string Amount of outstanding guarantee
     */
    public $safe_amount;

    /**
     * @var Term Document condition informations
     */
    public $term;

    /**
     * @var boolean Billing option (true is incl. taxes, false is excl. taxes and null is Company billing option)
     */
    public $ttc;

    /**
     * @var string Comments on this client
     */
    public $comment;

    /**
     * @var string Client business Number (SIRET), if current user is located in FR-DOM-TOM this field can be required
     */
    public $business_number;

    /**
     * @var string Client intra-community VAT number
     * Set the 'N/C' if Not Concerned, Not Known or Not Communicated
     * If current user is located in FR-DOM-TOM this field can be required
     */
    public $vat_number;

    /**
     * @param array $data Array to build the object
     */
    public function __construct(array $data)
    {
        $this->clientid = $data['clientid'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->address = isset($data['address']) ? new Address((array) $data['address']) : null;
        $this->code = $data['code'] ?? null;
        $this->legalform = $data['legalform'] ?? null;
        $this->activity_number = $data['activity_number'] ?? null;
        $this->immat_number = $data['immat_number'] ?? null;
        $this->bank_information = isset($data['bank_information']) ? new BankInformation((array) $data['bank_information']) : null;
        $this->delivery_address = isset($data['delivery_address']) ? new Address((array) $data['delivery_address']) : null;
        $this->phone = $data['phone'] ?? null;
        $this->mobile = $data['mobile'] ?? null;
        $this->fax = $data['fax'] ?? null;
        $this->website = $data['website'] ?? null;
        $this->safe_amount = $data['safe_amount'] ?? null;
        $this->term = isset($data['term']) ? new Term((array) $data['term']) : null;
        $this->ttc = $data['ttc'] ?? null;
        $this->comment = $data['comment'] ?? null;
        $this->business_number = $data['business_number'] ?? null;
        $this->vat_number = $data['vat_number'] ?? null;
    }
}
