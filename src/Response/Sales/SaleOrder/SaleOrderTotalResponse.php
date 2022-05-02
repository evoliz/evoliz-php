<?php

namespace Evoliz\Client\Response\Sales\SaleOrder;

use Evoliz\Client\Response\Sales\RebateResponse;
use Evoliz\Client\Response\Sales\SaleDocumentMarginResponse;

class SaleOrderTotalResponse
{
    /**
     * @var RebateResponse Document amount rebate
     */
    public $rebate;

    /**
     * @var float Total amount of the document excluding vat
     */
    public $vat_exclude;

    /**
     * @var float Total amount of vat
     */
    public $vat;

    /**
     * @var float Total amount of the document including vat
     */
    public $vat_include;

    /**
     * @var SaleDocumentMarginResponse Total sale margin information
     */
    public $margin = null;

    /**
     * @var float Total amount remaining on this document
     */
    public $net_to_pay;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->rebate = isset($data['rebate']) ? new RebateResponse($data['rebate']) : null;
        $this->vat_exclude = $data['vat_exclude'] ?? null;
        $this->vat = $data['vat'] ?? null;
        $this->vat_include = $data['vat_include'] ?? null;
        $this->margin = isset($data['margin']) ? new SaleDocumentMarginResponse($data['margin']) : null;
        $this->net_to_pay = $data['net_to_pay'] ?? null;
    }


}
