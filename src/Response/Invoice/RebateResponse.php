<?php

namespace Evoliz\Client\Response\Invoice;

class RebateResponse
{
    /**
     * @var float RebateResponse amount excluding vat in currency
     */
    public $amount_vat_exclude;

    /**
     * @var float Percentage rebate on the document in currency
     */
    public $percent = null;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->amount_vat_exclude = $data['amount_vat_exclude'] ?? null;
        $this->percent = $data['percent'] ?? null;
    }

}
