<?php

namespace Evoliz\Client\Model\Sales;

use Evoliz\Client\Exception\InvalidTypeException;
use Evoliz\Client\Model\Item;
use Evoliz\Client\Response\Invoice\InvoiceResponse;
use Evoliz\Client\Response\Invoice\SellDocItemResponse;

class Invoice
{
    const BASE_ENDPOINT = 'invoices';
    const RESPONSE_MODEL = InvoiceResponse::class;

    /**
     * @var string External Document number, must be unique
     */
    public $external_document_number;

    /**
     * @var string Document date
     */
    public $documentdate;

    /**
     * @var integer The client's id to attach the invoice to
     */
    public $clientid;

    /**
     * @var integer The client's contact id to adress the invoice to
     */
    public $contactid;

    /**
     * @var string Object on the document
     */
    public $object;

    /**
     * @var Term Invoice condition informations
     */
    public $term;

    /**
     * @var string Comments on the invoice with html
     */
    public $comment;

    /**
     * @var integer Analytic axis id, this field is accepted only when analytic option is enabled, required if invoice is checked in analytic configuration
     */
    public $analyticid;

    /**
     * @var float Invoice rebate in amount
     */
    public $global_rebate;

    /**
     * @var string Execution date of payment terms
     */
    public $execdate;

    /**
     * @var Retention Invoice retention information
     */
    public $retention;

    /**
     * @var boolean Indicate whether to include sale general conditions in the document PDF or not
     */
    public $include_sale_general_conditions = false;

    /**
     * @var array Invoice items
     */
    public $items = [];

    /**
     * @param array $data
     * @throws InvalidTypeException
     */
    public function __construct(array $data)
    {
        $this->clientid = $this->mapClientId($data);
        $this->analyticid = $this->mapAnalyticId($data);
        $this->global_rebate = $this->mapGlobalRebate($data);

        $this->external_document_number = $data['external_document_number'] ?? null;
        $this->documentdate = $data['documentdate'] ?? null;
        $this->contactid = $data['contactid'] ?? null; //@Todo : Handle this when the resource is updated
        $this->object = $data['object'] ?? null;
        $this->term = isset($data['term']) ? new Term((array) $data['term']) : null;

        if (isset($data['comment']) && $data['comment'] !== "") {
            $this->comment = $data['comment'];
        }

        $this->execdate = $data['execdate'] ?? null;
        $this->retention = isset($data['retention']) ? new Retention($data['retention']) : null;
        $this->include_sale_general_conditions = $data['include_sale_general_conditions'];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!($item instanceof Item) && !($item instanceof SellDocItemResponse)) {
                    throw new InvalidTypeException('Error : The given object is not of the right type', 401);
                }
                $this->items[] = $item;
            }
        }
    }

    /**
     * Map the clientid with the correct information
     * @param array $data
     * @return integer|null
     */
    private function mapClientId(array $data)
    {
        if (isset($data['client'])) {
            $clientid = $data['client']->clientid;
        } elseif (isset($data['clientid'])) {
            $clientid = $data['clientid'];
        }

        return $clientid ?? null;
    }

    /**
     * Map the analyticid with the correct information
     * @param array $data
     * @return integer|null
     */
    private function mapAnalyticId(array $data)
    {
        if (isset($data['analytic'])) {
            $analyticid = $data['analytic']->id;
        } elseif (isset($data['analyticid'])) {
            $analyticid = $data['analyticid'];
        }

        return $analyticid ?? null;
    }

    /**
     * Map the global_rebate with the correct information
     * @param array $data
     * @return float|null
     */
    private function mapGlobalRebate(array $data)
    {
        if (isset($data['total'])) {
            $global_rebate = $data['total']->rebate->amount_vat_exclude;
        } elseif (isset($data['global_rebate'])) {
            $global_rebate = $data['global_rebate'];
        }

        return $global_rebate ?? null;
    }
}
