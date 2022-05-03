<?php

namespace Evoliz\Client\Model\Sales;

use Evoliz\Client\Model\BaseModel;

class SaleOrder extends BaseModel
{
    /**
     * @var string External Document number, must be unique
     */
    public $external_document_number;

    /**
     * @var string Document date
     */
    public $documentdate;

    /**
     * @var integer The client's id to attach the sale order to
     */
    public $clientid;

    /**
     * @var integer The client's contact id to adress the sale order to
     */
    public $contactid;

    /**
     * @var string Object on the document
     */
    public $object;

    /**
     * @var Term Sale order condition informations
     */
    public $term;

    /**
     * @var string Comments on the sale order with html
     */
    public $comment;

    /**
     * @var integer Analytic axis id, this field is accepted only when analytic option is enabled, required if sale order is checked in analytic configuration
     */
    public $analyticid;

    public $retract;
    public $delivery_date;

    /**
     * @var float|string Invoice rebate in amount|percent
     */
    public $global_rebate;

    /**
     * @var boolean Indicate whether to include sale general conditions in the document PDF or not
     */
    public $include_sale_general_conditions = false;

    /**
     * @var array Array of Items without articleid or Items with articleid
     */
    public $items = [];

    /**
     * @param array $data Array to build the object
     */
    public function __construct(array $data)
    {
        $this->external_document_number = $data['external_document_number'] ?? null;
        $this->documentdate = $data['documentdate'] ?? null;
        $this->clientid = $data['clientid'] ?? null;
        $this->contactid = $data['contactid'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->term = isset($data['term']) ? new Term((array) $data['term']) : null;

        if (isset($data['comment']) && $data['comment'] !== "") {
            $this->comment = $data['comment'];
        }
        $this->analyticid = $data['analyticid'] ?? null;
        $this->retract = $data['retract'] ?? null;
        $this->delivery_date = $data['delivery_date'] ?? null;
        $this->global_rebate = $data['global_rebate'] ?? null;
        $this->include_sale_general_conditions = $data['include_sale_general_conditions'];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $this->items[] = $item;
            }
        }
    }
}
