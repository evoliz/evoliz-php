<?php

namespace Evoliz\Client\Model;

class PayTerm
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
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->paytermid = $data['paytermid'] ?? null;
        $this->label = $data['label'] ?? null;
    }

}
