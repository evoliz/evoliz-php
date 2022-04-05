<?php

namespace Evoliz\Client\Response\Sales\SaleOrder;

use Evoliz\Client\Response\Common\LinkedClientResponse;
use Evoliz\Client\Response\ResponseInterface;
use Evoliz\Client\Response\Sales\AnalyticResponse;
use Evoliz\Client\Response\Sales\CurrencyResponse;
use Evoliz\Client\Response\Sales\SellDocItemResponse;
use Evoliz\Client\Response\Sales\TermResponse;

class SaleOrderResponse implements ResponseInterface
{
    /**
     * @var integer Object unique identifier
     */
    public $orderid;

    /**
     * @var string Document number
     */
    public $document_number;

    /**
     * @var integer Document’s creator ID
     */
    public $userid;

    /**
     * @var LinkedClientResponse Linked client informations
     */
    public $client;

    /**
     * @var CurrencyResponse Company default currency
     */
    public $default_currency;

    /**
     * @var CurrencyResponse Document currency
     */
    public $document_currency = null;

    /**
     * @var SaleOrderTotalResponse Document total amounts
     */
    public $total;

    /**
     * @var SaleOrderTotalResponse Document total amounts in currency
     */
    public $currency_total = null;

    /**
     * @var integer Document status code
     */
    public $status_code;

    /**
     * @var string Document status
     */
    public $status;

    /**
     * @var StatusDatesResponse Document status dates
     */
    public $status_dates;

    /**
     * @var string object on the document
     */
    public $object;

    /**
     * @var string Document date
     */
    public $documentdate;

    /**
     * @var string Due date of the document
     */
    public $duedate;

    /**
     * @var string Execution date of payment terms
     */
    public $execdate;

    /**
     * @var string Delivery date of the document
     */
    public $delivery_date = null;

    /**
     * @var boolean Withdrawal period
     */
    public $retract;

    /**
     * @var TermResponse Document condition informations
     */
    public $term;

    /**
     * @var string Comments on the document with html
     */
    public $comment;

    /**
     * @var string Comments on the document without html
     */
    public $comment_clean;

    /**
     * @var string External Document number
     */
    public $external_document_number;

    /**
     * @var boolean Determines if the document is active
     */
    public $enabled;

    /**
     * @var AnalyticResponse Analytic axis of document
     */
    public $analytic;

    /**
     * @var string Link of document file
     */
    public $file;

    /**
     * @var string Link of linked documents list
     */
    public $links;

    /**
     * @var string Webdocument link
     */
    public $webdoc = null;

    /**
     * @var array Document item
     */
    public $items = [];

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->orderid = $data['orderid'] ?? null;
        $this->document_number = $data['document_number'] ?? null;
        $this->userid = $data['userid'] ?? null;
        $this->client = isset($data['client']) ? new LinkedClientResponse($data['client']) : null;
        $this->default_currency = isset($data['default_currency']) ? new CurrencyResponse($data['default_currency']) : null;
        $this->document_currency = isset($data['document_currency']) ? new CurrencyResponse($data['document_currency']) : null;
        $this->total = isset($data['total']) ? new SaleOrderTotalResponse($data['total']) : null;
        $this->currency_total = isset($data['currency_total']) ? new SaleOrderTotalResponse($data['currency_total']) : null;
        $this->status_code = $data['status_code'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->status_dates = isset($data['status_dates']) ? new StatusDatesResponse($data['status_dates']) : null;
        $this->object = $data['object'] ?? null;
        $this->documentdate = $data['documentdate'] ?? null;
        $this->duedate = $data['duedate'] ?? null;
        $this->execdate = $data['execdate'] ?? null;
        $this->delivery_date = $ddata['elivery_date'] ?? null;
        $this->retract = $data['retract'] ?? null;
        $this->term = isset($data['term']) ? new TermResponse($data['term']) : null;
        $this->comment = $data['comment'] ?? null;
        $this->comment_clean = $data['comment_clean'] ?? null;
        $this->external_document_number = $data['external_document_number'] ?? null;
        $this->enabled = $data['enabled'] ?? null;
        $this->analytic = isset($data['analytic']) ? new AnalyticResponse($data['analytic']) : null;
        $this->file = $data['file'] ?? null;
        $this->links = $data['links'] ?? null;
        $this->webdoc = $data['webdoc'] ?? null;

        if (isset($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $this->items[] = new SellDocItemResponse($itemData);
            }
        }
    }


    public function createFromResponse()
    {
        // TODO: Implement createFromResponse() method.
    }
}
