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

    /**
     * @var string Delivery date of the document
     */
    public $delivery_date = null;

    /**
     * @var boolean Withdrawal period
     */
    public $retract = null;

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
        $this->external_document_number = $data['external_document_number'];
        $this->documentdate = $data['documentdate'];

        $this->clientid = $this->extractClientId($data);

        $this->contactid = $data['contactid']; //@Todo : Handle this when the resource is updated
        $this->object = $data['object'];

        $this->term = isset($data['term']) ? new Term((array) $data['term']) : null;

        if (isset($data['comment']) && $data['comment'] !== "") {
            $this->comment = $data['comment'];
        }

        $this->analyticid = $this->extractAnalyticId($data);

        $this->retract = $data['retract'];
        $this->delivery_date = $data['delivery_date'];

        $this->global_rebate = $this->extractGlobalRebate($data);

        $this->include_sale_general_conditions = $data['include_sale_general_conditions'];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $this->items[] = $item;
            }
        }
    }

    /**
     * Extract the clientid field with the correct information
     * @param array $data Array to build the object
     * @return integer|null
     */
    private function extractClientId(array $data)
    {
        if (isset($data['client'])) {
            $clientid = $data['client']->clientid;
        } elseif (isset($data['clientid'])) {
            $clientid = $data['clientid'];
        }

        return $clientid ?? null;
    }

    /**
     * Extract the analyticid field with the correct information
     * @param array $data Array to build the object
     * @return integer|null
     */
    private function extractAnalyticId(array $data)
    {
        if (isset($data['analytic'])) {
            $analyticid = $data['analytic']->id;
        } elseif (isset($data['analyticid'])) {
            $analyticid = $data['analyticid'];
        }

        return $analyticid ?? null;
    }

    /**
     * Extract the global_rebate field with the correct information
     * @param array $data Array to build the object
     * @return float|null
     */
    private function extractGlobalRebate(array $data)
    {
        if (isset($data['total'])) {
            $global_rebate = $data['total']->rebate->amount_vat_exclude;
        } elseif (isset($data['global_rebate'])) {
            $global_rebate = $data['global_rebate'];
        }

        return $global_rebate ?? null;
    }
}
