<?php

namespace Evoliz\Client\Response\Article;

use Evoliz\Client\Model\Catalog\Article;
use Evoliz\Client\Response\Common\ClassificationResponse;
use Evoliz\Client\Response\ContactClient\CustomFieldResponse;
use Evoliz\Client\Response\ResponseInterface;

class ArticleResponse implements ResponseInterface
{
    /**
     * @var integer Object unique identifier
     */
    public $articleid;

    /**
     * @var integer Document’s creator ID
     */
    public $userid;

    /**
     * @var string Article reference with html
     */
    public $reference;

    /**
     * @var string Article reference without html
     */
    public $reference_clean;

    /**
     * @var string Article Type
     * "product" or "service"
     */
    public $nature = null;

    /**
     * @var ClassificationResponse Item sell classification information
     */
    public $sale_classification;

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
     * @var float Item weight
     */
    public $weight = null;

    /**
     * @var string Quantity unit
     */
    public $unit = null;

    /**
     * @var float Article unit price excluding vat
     */
    public $unit_price_vat_exclude;

    /**
     * @var float Article unit price including vat
     */
    public $unit_price_vat_include;

    /**
     * @var float Article VAT rate
     */
    public $vat;

    /**
     * @var boolean Billing option (true is incl. taxes, false is excl. taxes and null is Company billing option)
     */
    public $ttc = null;

    /**
     * @var ClassificationResponse Item purchase classification information
     */
    public $purchase_classification = null;

    /**
     * @var MarginResponse Sale margin information
     */
    public $margin = null;

    /**
     * @var LinkedSupplierResponse Linked supplier informations
     */
    public $supplier;

    /**
     * @var string Article reference from the supplier with html
     */
    public $supplier_reference = null;

    /**
     * @var string Article reference from the supplier without html
     */
    public $supplier_reference_clean = null;

    /**
     * @var boolean Stock management for the article
     */
    public $stock_management;

    /**
     * @var float Article stocked quantity
     */
    public $stocked_quantity = null;

    /**
     * @var boolean Determines if the article is active
     */
    public $enabled;

    /**
     * @var string Link to article picture file
     */
    public $picture_link = null;

    /**
     * @var array Custom fields collection
     */
    public $custom_fields = [];

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->articleid = $data['articleid'] ?? null;
        $this->userid = $data['userid'] ?? null;
        $this->reference = $data['reference'] ?? null;
        $this->reference_clean = $data['reference_clean'] ?? null;
        $this->nature = $data['nature'] ?? null;
        $this->sale_classification = isset($data['sale_classification']) ? new ClassificationResponse($data['sale_classification']) : null;
        $this->designation = $data['designation'] ?? null;
        $this->designation_clean = $data['designation_clean'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->weight = $data['weight'] ?? null;
        $this->unit = $data['unit'] ?? null;
        $this->unit_price_vat_exclude = $data['unit_price_vat_exclude'] ?? null;
        $this->unit_price_vat_include = $data['unit_price_vat_include'] ?? null;
        $this->vat = $data['vat'] ?? null;
        $this->ttc = $data['ttc'] ?? null;
        $this->purchase_classification = isset($data['purchase_classification']) ? new ClassificationResponse($data['purchase_classification']) : null;
        $this->margin = isset($data['margin']) ? new MarginResponse($data['margin']) : null;
        $this->supplier = isset($data['supplier']) ? new LinkedSupplierResponse($data['supplier']) : null;
        $this->supplier_reference = $data['supplier_reference'] ?? null;
        $this->supplier_reference_clean = $data['supplier_reference_clean'] ?? null;
        $this->stock_management = $data['stock_management'] ?? null;
        $this->stocked_quantity = $data['stocked_quantity'] ?? null;
        $this->enabled = $data['enabled'] ?? null;
        $this->picture_link = $data['picture_link'] ?? null;

        if (isset($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $custom_field_label => $custom_field_value) {
                $this->custom_fields[$custom_field_label] = new CustomFieldResponse($custom_field_value);
            }
        }
    }

    /**
     * Build Article from ArticleResponse
     * @return Article
     */
    public function createFromResponse(): Article
    {
        return new Article((array) $this);
    }
}
