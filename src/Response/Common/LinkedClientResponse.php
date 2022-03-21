<?php

namespace Evoliz\Client\Response\Common;

class LinkedClientResponse
{
    /**
     * @var integer Linked client Id
     */
    public $clientid;

    /**
     * @var string Client Code identifier
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
     * @param array $data response array to build the object
     */
    public function __construct(array $data)
    {
        $this->clientid = $data['clientid'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->civility = $data['civility'] ?? null;
        $this->name = $data['name'] ?? null;
    }

}
