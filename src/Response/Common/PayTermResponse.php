<?php

namespace Evoliz\Client\Response\Common;

class PayTermResponse
{
    /**
     * @var integer Payment term identifier
     */
    public $paytermid;

    /**
     * @var string Payment term label
     */
    public $label;

    /**
     * @param array $data response array to build the object
     */
    public function __construct(array $data)
    {
        $this->paytermid = $data['paytermid'] ?? null;
        $this->label = $data['label'] ?? null;
    }

}
