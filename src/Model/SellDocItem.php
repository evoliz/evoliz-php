<?php

namespace Evoliz\Client\Model;

class SellDocItem
{
    /**
     * @var integer
     */
    private $itemid;

    /**
     * @var integer
     */
    private $articleid = null;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $reference_clean;

    /**
     * @var string
     */
    private $designation;

    /**
     * @var string
     */
    private $designation_clean;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var string
     */
    private $unit;

    /**
     * @var float
     */
    private $unit_price_vat_exclude;

    /**
     * @var float
     */
    private $unit_price_vat_exclude_currency = null;

    /**
     * @var float
     */
    private $vat;

    /**
     * @var ItemTotal
     */
    private $total = null;

    /**
     * @var ItemTotal
     */
    private $currency_total = null;

    /**
     * @var SaleClassification
     */
    private $sale_classification;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {;
        $this->itemid = $data['itemid'] ?? null;
        $this->articleid = $data['articleid'] ?? null;
        $this->reference = $data['reference'] ?? null;
        $this->reference_clean = $data['reference_clean'] ?? null;
        $this->designation = $data['designation'] ?? null;
        $this->designation_clean = $data['designation_clean'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->unit = $data['unit'] ?? null;
        $this->unit_price_vat_exclude = $data['unit_price_vat_exclude'] ?? null;
        $this->unit_price_vat_exclude_currency = $data['unit_price_vat_exclude_currency'] ?? null;
        $this->vat = $data['vat'] ?? null;
        $this->total = isset($data['total']) ? new ItemTotal($data['total']) : null;
        $this->currency_total = isset($data['currency_total']) ? new ItemTotal($data['currency_total']) : null;
        $this->sale_classification = isset($data['sale_classification']) ? new SaleClassification($data['sale_classification']) : null;
    }

    /**
     * @return int
     */
    public function getItemid()
    {
        return $this->itemid;
    }

    /**
     * @param int $itemid
     */
    public function setItemid($itemid)
    {
        $this->itemid = $itemid;
    }

    /**
     * @return int
     */
    public function getArticleid()
    {
        return $this->articleid;
    }

    /**
     * @param int $articleid
     */
    public function setArticleid($articleid)
    {
        $this->articleid = $articleid;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReferenceClean()
    {
        return $this->reference_clean;
    }

    /**
     * @param string $reference_clean
     */
    public function setReferenceClean($reference_clean)
    {
        $this->reference_clean = $reference_clean;
    }

    /**
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * @param string $designation
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }

    /**
     * @return string
     */
    public function getDesignationClean()
    {
        return $this->designation_clean;
    }

    /**
     * @param string $designation_clean
     */
    public function setDesignationClean($designation_clean)
    {
        $this->designation_clean = $designation_clean;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return float
     */
    public function getUnitPriceVatExclude()
    {
        return $this->unit_price_vat_exclude;
    }

    /**
     * @param float $unit_price_vat_exclude
     */
    public function setUnitPriceVatExclude($unit_price_vat_exclude)
    {
        $this->unit_price_vat_exclude = $unit_price_vat_exclude;
    }

    /**
     * @return float
     */
    public function getUnitPriceVatExcludeCurrency()
    {
        return $this->unit_price_vat_exclude_currency;
    }

    /**
     * @param float $unit_price_vat_exclude_currency
     */
    public function setUnitPriceVatExcludeCurrency($unit_price_vat_exclude_currency)
    {
        $this->unit_price_vat_exclude_currency = $unit_price_vat_exclude_currency;
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
     * @return ItemTotal
     */
    public function getTotal(): ItemTotal
    {
        return $this->total;
    }

    /**
     * @param ItemTotal $total
     */
    public function setTotal(ItemTotal $total)
    {
        $this->total = $total;
    }

    /**
     * @return ItemTotal
     */
    public function getCurrencyTotal(): ItemTotal
    {
        return $this->currency_total;
    }

    /**
     * @param ItemTotal $currency_total
     */
    public function setCurrencyTotal(ItemTotal $currency_total)
    {
        $this->currency_total = $currency_total;
    }

    /**
     * @return SaleClassification
     */
    public function getSaleClassification(): SaleClassification
    {
        return $this->sale_classification;
    }

    /**
     * @param SaleClassification $sale_classification
     */
    public function setSaleClassification(SaleClassification $sale_classification)
    {
        $this->sale_classification = $sale_classification;
    }




}
