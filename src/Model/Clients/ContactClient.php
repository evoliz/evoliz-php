<?php

namespace Evoliz\Client\Model\Clients;

use Evoliz\Client\Model\BaseModel;

class ContactClient extends BaseModel
{
    /**
     * @var integer The client's id to attach the contact to
     */
    public $clientid;

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
     * @var string Contact Job/Function
     */
    public $profil;

    /**
     * @var string Contact email address
     */
    public $email;

    /**
     * @var string Contact consent informations
     */
    public $consent;

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
     * @param array $data Array to build the object
     */
    public function __construct(array $data)
    {
        $this->clientid = $this->extractClientId($data);
        $this->civility = $data['civility'] ?? null;
        $this->lastname = $data['lastname'] ?? null;
        $this->firstname = $data['firstname'] ?? null;
        $this->profil = $data['profil'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->consent = $data['consent'] ?? null;
        $this->label_tel_primary = $data['label_tel_primary'] ?? null;
        $this->tel_primary = $data['tel_primary'] ?? null;
        $this->label_tel_secondary = $data['label_tel_secondary'] ?? null;
        $this->tel_secondary = $data['tel_secondary'] ?? null;
        $this->label_tel_tertiary = $data['label_tel_tertiary'] ?? null;
        $this->tel_tertiary = $data['tel_tertiary'] ?? null;
    }

    /**
     * Extract the clientid field with the correct information
     * @param array $data Array to build the object
     * @return integer|null
     */
    private function extractClientId(array $data)
    {
        $clientid = null;

        if (isset($data['client'])) {
            $clientid = $data['client']->clientid;
        } elseif (isset($data['clientid'])) {
            $clientid = $data['clientid'];
        }

        return $clientid;
    }
}
