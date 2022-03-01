<?php

namespace Evoliz\Client\Model;

class PayType
{
    /**
     * @var integer
     */
    private $paytypeid;

    /**
     * @var string
     */
    private $label;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->paytypeid = isset($data['paytypeid']) ? $data['paytypeid'] : null;
        $this->label = isset($data['label']) ? $data['label'] : null;
    }

    /**
     * @return int
     */
    public function getPayTypeId()
    {
        return $this->paytypeid;
    }

    /**
     * @param int $paytypeid
     */
    public function setPayTypeId($paytypeid)
    {
        $this->paytypeid = $paytypeid;
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