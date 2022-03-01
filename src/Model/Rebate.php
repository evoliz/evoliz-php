<?php

namespace Evoliz\Client\Model;

class Rebate
{
    /**
     * @var float
     */
    private $amount_vat_exclude;

    /**
     * @var float
     */
    private $percent = null;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->amount_vat_exclude = isset($data['amount_vat_exclude']) ? $data['amount_vat_exclude'] : null;
        $this->percent = isset($data['percent']) ? $data['percent'] : null;
    }

    /**
     * @return float
     */
    public function getAmountVatExclude()
    {
        return $this->amount_vat_exclude;
    }

    /**
     * @param float $amount_vat_exclude
     */
    public function setAmountVatExclude($amount_vat_exclude)
    {
        $this->amount_vat_exclude = $amount_vat_exclude;
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }

}