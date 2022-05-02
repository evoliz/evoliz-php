<?php

namespace Evoliz\Client\Response\Article;

class MarginResponse
{
    /**
     * @var float Purchase unit price
     */
    public $purchase_unit_price_vat_exclude;

    /**
     * @var float Margin coefficient
     */
    public $coefficient;

    /**
     * @var float Margin percent
     */
    public $percent;

    /**
     * @var float Margin amount
     */
    public $amount;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->purchase_unit_price_vat_exclude = $data['purchase_unit_price_vat_exclude'] ?? null;
        $this->coefficient = $data['coefficient'] ?? null;
        $this->percent = $data['percent'] ?? null;
        $this->amount = $data['amount'] ?? null;
    }
}
