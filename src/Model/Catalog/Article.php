<?php

namespace Evoliz\Client\Model\Catalog;

use Evoliz\Client\Model\BaseModel;

class Article extends BaseModel
{
    /**
     * @var int
     */
    public $articleid;

    /**
     * @var string Article reference
     */
    public $reference;

    /**
     * @var string Article designation
     */
    public $designation;

    /**
     * @var string Article type
     * "product" or "service"
     */
    public $nature;

    /**
     * @var float Article quantity
     */
    public $quantity;

    /**
     * @var float Article weight
     */
    public $weight;

    /**
     * @var string Article quantity unit
     */
    public $unit;

    /**
     * @var integer Article sell classification id, only accepted when sell classifications are enabled
     */
    public $sale_classificationid;

    /**
     * @var float Article unit price
     * Excluding or including vat according to ttc field value
     * Or defaults to current company configuration if ttc is not filled
     */
    public $unit_price;

    /**
     * @var float Article VAT rate
     */
    public $vat_rate;

    /**
     * @var boolean Billing option (true is incl. taxes, false is excl. taxes and null is Company billing option)
     */
    public $ttc;

    /**
     * @var float Article purchase unit price, must be less than unit price vat excluded
     * >= 0.01
     */
    public $purchase_unit_price_vat_exclude;

    /**
     * @var integer Article buy classification id
     */
    public $purchase_classificationid;

    /**
     * @var integer Linked supplier Id
     */
    public $supplierid;

    /**
     * @var string Article reference from the supplier
     */
    public $supplier_reference;

    /**
     * @var boolean Stock management for the article
     */
    public $stock_management;

    /**
     * @var float Article stocked quantity
     */
    public $stocked_quantity;

    /**
     * @param array $data Array to build the object
     */
    public function __construct(array $data)
    {
        $this->articleid = $data['articleid'] ?? null;
        $this->reference = $data['reference'] ?? null;
        $this->designation = $data['designation'] ?? null;
        $this->nature = $data['nature'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->weight = $data['weight'] ?? null;
        $this->unit = $data['unit'] ?? null;
        $this->sale_classificationid = $this->extractSaleClassificationId($data);
        $this->unit_price = $this->extractUnitPrice($data);
        $this->vat_rate = $this->extractVatRate($data);
        $this->ttc = $data['ttc'] ?? null;
        $this->purchase_unit_price_vat_exclude = $this->extractPurchaseUnitPriceVatExclude($data);
        $this->purchase_classificationid = $this->extractPurchaseClassificationId($data);
        $this->supplierid = $this->extractSupplierId($data);
        $this->supplier_reference = $data['supplier_reference'] ?? null;
        $this->stock_management = $data['stock_management'] ?? null;
        $this->stocked_quantity = $data['stock_management'] ? $data['stocked_quantity'] : null;
    }

    /**
     * Extract the sale_classificationid field with the correct information
     *
     * @param  array $data Array to build the object
     * @return integer|null
     */
    private function extractSaleClassificationId(array $data)
    {
        if (isset($data['sale_classificationid'])) {
            $sale_classificationid = $data['sale_classificationid'];
        } elseif (isset($data['sale_classification'])) {
            $sale_classificationid = $data['sale_classification']->id;
        }

        return $sale_classificationid ?? null;
    }

    /**
     * Extract the unit_price field with the correct information
     *
     * @param  array $data Array to build the object
     * @return float|null
     */
    private function extractUnitPrice(array $data)
    {
        if (isset($data['unit_price'])) {
            $unit_price = $data['unit_price'];
        } elseif (isset($data['unit_price_vat_include']) || isset($data['unit_price_vat_exclude'])) {
            $unit_price = $data['ttc'] ? $data['unit_price_vat_include'] : $data['unit_price_vat_exclude'] ;
        }

        return $unit_price ?? null;
    }

    /**
     * Extract the vat_rate field with the correct information
     *
     * @param  array $data Array to build the object
     * @return float|null
     */
    private function extractVatRate(array $data)
    {
        if (isset($data['vat_rate'])) {
            $vat_rate = $data['vat_rate'];
        } elseif (isset($data['vat'])) {
            $vat_rate = $data['vat'];
        }

        return $vat_rate ?? null;
    }

    /**
     * Extract the purchase_unit_price_vat_exclude field with the correct information
     *
     * @param  array $data Array to build the object
     * @return float|null
     */
    private function extractPurchaseUnitPriceVatExclude(array $data)
    {
        if (isset($data['purchase_unit_price_vat_exclude'])) {
            $purchase_unit_price_vat_exclude = $data['purchase_unit_price_vat_exclude'];
        } elseif (isset($data['margin'])) {
            $purchase_unit_price_vat_exclude = $data['margin']->purchase_unit_price_vat_exclude;
        }

        return $purchase_unit_price_vat_exclude ?? null;
    }

    /**
     * Extract the purchase_classificationid field with the correct information
     *
     * @param  array $data Array to build the object
     * @return integer|null
     */
    private function extractPurchaseClassificationId(array $data)
    {
        if (isset($data['purchase_classificationid'])) {
            $purchase_classificationid = $data['purchase_classificationid'];
        } elseif (isset($data['purchase_classification'])) {
            $purchase_classificationid = $data['purchase_classification']->id;
        }

        return $purchase_classificationid ?? null;
    }

    /**
     * Extract the supplierid field with the correct information
     *
     * @param  array $data Array to build the object
     * @return integer|null
     */
    private function extractSupplierId(array $data)
    {
        if (isset($data['supplierid'])) {
            $supplierid = $data['supplierid'];
        } elseif (isset($data['supplier'])) {
            $supplierid = $data['supplier']->supplierid;
        }

        return $supplierid ?? null;
    }
}
