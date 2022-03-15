<?php

namespace Evoliz\Client\Model;

use Evoliz\Client\Exception\InvalidTypeException;
use Evoliz\Client\Model\Response\InvoiceResponse;

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
        $this->external_document_number = $data['external_document_number'] ?? null;
        $this->documentdate = $data['documentdate'] ?? null;
        $this->clientid = $data['clientid'] ?? null;
        $this->contactid = $data['contactid'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->term = isset($data['term']) ? new Term($data['term']) : null;
        $this->comment = $data['comment'] ?? null;
        $this->analyticid = $data['analyticid'] ?? null;
        $this->global_rebate = $data['global_rebate'] ?? null;
        $this->execdate = $data['execdate'] ?? null;
        $this->retention = isset($data['retention']) ? new Retention($data['retention']) : null;
        $this->include_sale_general_conditions = $data['include_sale_general_conditions'];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!is_a($item, Item::class)) {
                    throw new InvalidTypeException('Error : The given object is not of the ' . Item::class . ' type', 401);
                }
                $this->items[] = $item;
            }
        }
    }
}
