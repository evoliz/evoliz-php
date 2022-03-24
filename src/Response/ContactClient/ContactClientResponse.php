<?php

namespace Evoliz\Client\Response\ContactClient;

use Evoliz\Client\Model\Clients\ContactClient;
use Evoliz\Client\Response\Common\LinkedClientResponse;
use Evoliz\Client\Response\ResponseInterface;

class ContactClientResponse implements ResponseInterface
{
    /**
     * @var integer Object unique identifier
     */
    public $contactid;

    /**
     * @var integer Contact client’s creator ID
     */
    public $userid;

    /**
     * @var LinkedClientResponse Linked client informations
     */
    public $client;

    /**
     * @var string Contact client civility
     */
    public $civility;

    /**
     * @var string Contact last name
     */
    public $lastname;

    /**
     * @var string Contact first name
     */
    public $firstname;

    /**
     * @var string Contact email address
     */
    public $email;

    /**
     * @var string Contact Job/Function
     */
    public $profil;

    /**
     * @var string Primary phone label
     */
    public $label_tel_primary;

    /**
     * @var string Primary phone number
     */
    public $tel_primary;

    /**
     * @var string Secondary phone label
     */
    public $label_tel_secondary;

    /**
     * @var string Secondary phone number
     */
    public $tel_secondary;

    /**
     * @var string Tertiary phone label
     */
    public $label_tel_tertiary;

    /**
     * @var string Tertiary phone number
     */
    public $tel_tertiary;

    /**
     * @var bool Determines if the contact is active
     */
    public $enabled;

    /**
     * @var string Contact consent informations
     */
    public $consent;

    /**
     * @var array Custom fields collection
     */
    public $custom_fields = [];

    /**
     * @param array $data response array to build the object
     */
    public function __construct(array $data)
    {
        $this->contactid = $data['contactid'] ?? null;
        $this->userid = $data['userid'] ?? null;
        $this->client = isset($data['client']) ? new LinkedClientResponse($data['client']) : null;
        $this->civility = $data['civility'] ?? null;
        $this->lastname = $data['lastname'] ?? null;
        $this->firstname = $data['firstname'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->profil = $data['profil'] ?? null;
        $this->label_tel_primary = $data['label_tel_primary'] ?? null;
        $this->tel_primary = $data['tel_primary'] ?? null;
        $this->label_tel_secondary = $data['label_tel_secondary'] ?? null;
        $this->tel_secondary = $data['tel_secondary'] ?? null;
        $this->label_tel_tertiary = $data['label_tel_tertiary'] ?? null;
        $this->tel_tertiary = $data['tel_tertiary'] ?? null;
        $this->enabled = $data['enabled'] ?? null;
        $this->consent = $data['consent'] ?? null;

        if (isset($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $custom_field_label => $custom_field_value) {
                $this->custom_fields[$custom_field_label] = new CustomFieldResponse($custom_field_value);
            }
        }
    }

    /**
     * Build ContactClient from ContactClientResponse
     * @return ContactClient
     */
    public function createFromResponse(): ContactClient
    {
        return new ContactClient((array) $this);
    }
}
