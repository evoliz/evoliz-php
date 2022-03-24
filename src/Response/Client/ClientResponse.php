<?php

namespace Evoliz\Client\Response\Client;

use Evoliz\Client\Model\Clients\Client\Client;
use Evoliz\Client\Response\ContactClient\CustomFieldResponse;
use Evoliz\Client\Response\ResponseInterface;

class ClientResponse implements ResponseInterface
{
    /**
     * @var integer Object unique identifier
     */
    public $clientid;

    /**
     * @var integer Client’s creator ID
     */
    public $userid;

    /**
     * @var string Client code identifier
     */
    public $code;

    /**
     * @var string Client civility
     */
    public $civility;

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
     * @var string Client's company legal form
     */
    public $legalform;

    /**
     * @var string Business Number (SIRET)
     */
    public $business_number;

    /**
     * @var string Main activity code (APE, NAF)
     */
    public $activity_number;

    /**
     * @var string Intra-community VAT number
     * Set the 'N/C' if Not Concerned, Not Known or Not Communicated
     */
    public $vat_number;

    /**
     * @var string Client's company registration number (RCS, RM)
     */
    public $immat_number;

    /**
     * @var BankInformationResponse Bank informations
     */
    public $bank_information;

    /**
     * @var AddressResponse Address informations
     */
    public $address;

    /**
     * @var AddressResponse Delivery Address informations
     */
    public $delivery_address;

    /**
     * @var string Phone number
     */
    public $phone;

    /**
     * @var string Cell phone number
     */
    public $mobile;

    /**
     * @var string Cell phone number
     */
    public $fax;

    /**
     * @var string Website
     */
    public $website;

    /**
     * @var float Amount of outstanding guarantee
     */
    public $safe_amount;

    /**
     * @var TermResponse Document condition informations
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
     * @var boolean Determines if the client is active
     */
    public $enabled;

    /**
     * @var array Custom fields collection
     */
    public $custom_fields = [];

    /**
     * @param array $data response array to build the object
     */
    public function __construct(array $data)
    {
        $this->clientid = $data['clientid'] ?? null;
        $this->userid = $data['userid'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->civility = $data['civility'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->legalform = $data['legalform'] ?? null;
        $this->business_number = $data['business_number'] ?? null;
        $this->activity_number = $data['activity_number'] ?? null;
        $this->vat_number = $data['vat_number'] ?? null;
        $this->immat_number = $data['immat_number'] ?? null;
        $this->bank_information = isset($data['bank_information']) ? new BankInformationResponse($data['bank_information']) : null;
        $this->address = isset($data['address']) ? new AddressResponse($data['address']) : null;
        $this->delivery_address = isset($data['delivery_address']) ? new AddressResponse($data['delivery_address']) : null;
        $this->phone = $data['phone'] ?? null;
        $this->mobile = $data['mobile'] ?? null;
        $this->fax = $data['fax'] ?? null;
        $this->website = $data['website'] ?? null;
        $this->safe_amount = $data['safe_amount'] ?? null;
        $this->term = isset($data['term']) ? new TermResponse($data['term']) : null;
        $this->ttc = $data['ttc'] ?? null;
        $this->comment = $data['comment'] ?? null;
        $this->enabled = $data['enabled'] ?? null;

        if (isset($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $custom_field_label => $custom_field_value) {
                $this->custom_fields[$custom_field_label] = new CustomFieldResponse($custom_field_value);
            }
        }
    }

    /**
     * Build Client from ClientResponse
     * @return Client
     */
    public function createFromResponse(): Client
    {
        return new Client((array) $this);
    }
}
