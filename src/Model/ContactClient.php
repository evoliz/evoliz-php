<?php

namespace Evoliz\Client\Model;

class ContactClient
{
    /**
     * @var integer
     */
    private $clientid;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $civility;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $profil;

    /**
     * @var string
     */
    private $consent;

    /**
     * @var string
     */
    private $label_tel_primary;

    /**
     * @var string
     */
    private $tel_primary;

    /**
     * @var string
     */
    private $label_tel_secondary;

    /**
     * @var string
     */
    private $tel_secondary;

    /**
     * @var string
     */
    private $label_tel_tertiary;

    /**
     * @var string
     */
    private $tel_tertiary;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->clientid = $data['clientid'] ?? null;
        $this->lastname = $data['lastname'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->civility = $data['civility'] ?? null;
        $this->firstname = $data['firstname'] ?? null;
        $this->profil = $data['profil'] ?? null;
        $this->consent = $data['consent'] ?? null;
        $this->label_tel_primary = $data['label_tel_primary'] ?? null;
        $this->tel_primary = $data['tel_primary'] ?? null;
        $this->label_tel_secondary = $data['label_tel_secondary'] ?? null;
        $this->tel_secondary = $data['tel_secondary'] ?? null;
        $this->label_tel_tertiary = $data['label_tel_tertiary'] ?? null;
        $this->tel_tertiary = $data['tel_tertiary'] ?? null;
    }

}
