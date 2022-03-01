<?php

namespace Evoliz\Client\Model;

class ItemTotal
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
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->rebate = isset($data['rebate']) ? new Rebate($data['rebate']) : null;
        $this->vat_exclude = isset($data['vat_exclude']) ? $data['vat_exclude'] : null;
        $this->vat = isset($data['vat']) ? $data['vat'] : null;
        $this->vat_include = isset($data['vat_include']) ? $data['vat_include'] : null;
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

}