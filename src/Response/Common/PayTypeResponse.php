<?php

namespace Evoliz\Client\Response\Common;

class PayTypeResponse
{
    /**
     * @var integer Payment type identifier
     */
    public $paytypeid;

    /**
     * @var string Payment type label
     */
    public $label;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->paytypeid = $data['paytypeid'] ?? null;
        $this->label = $data['label'] ?? null;
    }

}
