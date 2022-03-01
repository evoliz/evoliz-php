<?php

namespace Evoliz\Client\Model;

class SellDocTotal
{
    /**
     * @var Rebate
     */
    private $rebate;

    /**
     * @var float
     */
    private $vat_exclude;

    /**
     * @var float
     */
    private $vat;

    /**
     * @var float
     */
    private $vat_include;

    /**
     * @var float
     */
    private $advance;

    /**
     * @var float
     */
    private $paid;

    /**
     * @var float
     */
    private $net_to_pay;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->rebate = isset($data['rebate']) ? new Rebate($data['rebate']) : null;
        $this->vat_exclude = isset($data['vat_exclude']) ? $data['vat_exclude'] : null;
        $this->vat = isset($data['vat']) ? $data['vat'] : null;
        $this->vat_include = isset($data['vat_include']) ? $data['vat_include'] : null;
        $this->advance = isset($data['advance']) ? $data['advance'] : null;
        $this->paid = isset($data['paid']) ? $data['paid'] : null;
        $this->net_to_pay = isset($data['net_to_pay']) ? $data['net_to_pay'] : null;
    }

    /**
     * @return Rebate
     */
    public function getRebate()
    {
        return $this->rebate;
    }

    /**
     * @param Rebate $rebate
     */
    public function setRebate($rebate)
    {
        $this->rebate = $rebate;
    }

    /**
     * @return float
     */
    public function getVatExclude()
    {
        return $this->vat_exclude;
    }

    /**
     * @param float $vat_exclude
     */
    public function setVatExclude($vat_exclude)
    {
        $this->vat_exclude = $vat_exclude;
    }

    /**
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param float $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return float
     */
    public function getVatInclude()
    {
        return $this->vat_include;
    }

    /**
     * @param float $vat_include
     */
    public function setVatInclude($vat_include)
    {
        $this->vat_include = $vat_include;
    }

    /**
     * @return float
     */
    public function getAdvance()
    {
        return $this->advance;
    }

    /**
     * @param float $advance
     */
    public function setAdvance($advance)
    {
        $this->advance = $advance;
    }

    /**
     * @return float
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param float $paid
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }

    /**
     * @return float
     */
    public function getNetToPay()
    {
        return $this->net_to_pay;
    }

    /**
     * @param float $net_to_pay
     */
    public function setNetToPay($net_to_pay)
    {
        $this->net_to_pay = $net_to_pay;
    }

}