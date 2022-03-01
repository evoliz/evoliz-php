<?php

namespace Evoliz\Client\Model;

class PayTerm
{
    /**
     * @var integer
     */
    private $paytermid;

    /**
     * @var string
     */
    private $label;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->paytermid = isset($data['paytermid']) ? $data['paytermid'] : null;
        $this->label = isset($data['label']) ? $data['label'] : null;
    }

    /**
     * @return int
     */
    public function getPayTermId()
    {
        return $this->paytermid;
    }

    /**
     * @param int $paytermid
     */
    public function setPayTermId($paytermid)
    {
        $this->paytermid = $paytermid;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

}