<?php

namespace Evoliz\Client\Response\Sales;

class SaleDocumentMarginResponse
{
    /**
     * @var float Total purchase price
     */
    public $purchase_price_vat_exclude;

    /**
     * @var float Total margin percent
     */
    public $percent;

    /**
     * @var float Total margin amount
     */
    public $amount;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->purchase_price_vat_exclude = $data['purchase_price_vat_exclude'] ?? null;
        $this->percent = $data['percent'] ?? null;
        $this->amount = $data['amount'] ?? null;
    }
}
