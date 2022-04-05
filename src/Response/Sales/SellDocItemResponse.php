<?php

namespace Evoliz\Client\Response\Sales;

use Evoliz\Client\Response\Common\ClassificationResponse;

class SellDocItemResponse
{
    /**
     * @var integer Item id
     */
    public $itemid;

    /**
     * @var integer Article unique identifier
     */
    public $articleid = null;

    /**
     * @var string Article reference with html
     */
    public $reference;

    /**
     * @var string Article reference without html
     */
    public $reference_clean;

    /**
     * @var string Article designation with html
     */
    public $designation;

    /**
     * @var string Article designation without html
     */
    public $designation_clean;

    /**
     * @var float Article quantity
     */
    public $quantity;

    /**
     * @var string Quantity unit
     */
    public $unit;

    /**
     * @var float Article unit price excluding vat
     */
    public $unit_price_vat_exclude;

    /**
     * @var float Article unit price excluding vat in currency
     */
    public $unit_price_vat_exclude_currency = null;

    /**
     * @var float Article VAT rate
     */
    public $vat;

    /**
     * @var ItemTotalResponse Document total amounts
     */
    public $total = null;

    /**
     * @var ItemTotalResponse Document total amounts in currency
     */
    public $currency_total = null;

    /**
     * @var ClassificationResponse Item classification information
     */
    public $sale_classification;

    /**
     * @param array $data Response array to build the object
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
        $this->total = isset($data['total']) ? new ItemTotalResponse($data['total']) : null;
        $this->currency_total = isset($data['currency_total']) ? new ItemTotalResponse($data['currency_total']) : null;
        $this->sale_classification = isset($data['sale_classification']) ? new ClassificationResponse($data['sale_classification']) : null;
    }

}
