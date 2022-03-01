<?php

namespace Evoliz\Client\Model;

class LinkedClient
{

    /**
     * @var integer
     */
    private $clientid;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $civility;

    /**
     * @var string
     */
    private $name;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->clientid = isset($data['clientid']) ? $data['clientid'] : null;
        $this->code = isset($data['code']) ? $data['code'] : null;
        $this->civility = isset($data['civility']) ? $data['civility'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
    }

    /**
     * @return int
     */
    public function getClientid()
    {
        return $this->clientid;
    }

    /**
     * @param int $clientid
     */
    public function setClientid($clientid)
    {
        $this->clientid = $clientid;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * @param string $civility
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}