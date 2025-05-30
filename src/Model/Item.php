<?php

namespace Evoliz\Client\Model;

class Item extends BaseModel
{
    /**
     * @var integer Article unique identifier
     */
    public $articleid;

    /**
     * @var string Item reference with html (overrided if articleid is set)
     */
    public $reference;

    /**
     * @var string Item designation with html (overrided if articleid is set)
     */
    public $designation;

    /**
     * @var float Item quantity (overrided if articleid is set)
     */
    public $quantity;

    /**
     * @var string Quantity unit (overrided if articleid is set)
     */
    public $unit;

    /**
     * @var float Item unit price excluding vat (overrided if articleid is set)
     */
    public $unit_price_vat_exclude;

    /**
     * @var float Item unit price excluding or including vat depending on prices_include_vat attr (overrided if articleid is set)
     */
    public $unit_price;

    /**
     * @var float Item VAT rate (overrided if articleid is set)
     */
    public $vat_rate;

    /**
     * @var float Item rebate amount (overrided if articleid is set)
     */
    public $rebate;

    /**
     * @var integer Item sell classfication id, only accepted when sell classification are enabled
     * Overrided if articleid is set
     */
    public $sale_classificationid;

    /**
     * @param array $data Array to build the object
     */
    public function __construct(array $data, bool $prices_vat_include = false)
    {
        $this->articleid = $data['articleid'] ?? null;
        $this->reference = $data['reference'] ?? null;
        $this->designation = $data['designation'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->unit = $data['unit'] ?? null;
        $this->unit_price = $data['unit_price'] ?? null;
        // Handling deprecated field unit_price_vat_exclude to prevent existing code break
        if (!$this->unit_price && isset($data['unit_price_vat_exclude']) && !$prices_vat_include) {
            $this->unit_price_vat_exclude = $data['unit_price_vat_exclude'];
        }
        $this->vat_rate = $data['vat_rate'] ?? null;
        $this->rebate = $data['rebate'] ?? null;
        $this->sale_classificationid = $data['sale_classificationid'] ?? null;
    }
}
