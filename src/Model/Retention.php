<?php

namespace Evoliz\Client\Model;

class Retention
{
    /**
     * @var float Retention percent
     */
    public $percent;

    /**
     * @var float Retention amount
     */
    public $amount;

    /**
     * @var float Retention amount in currency
     */
    public $currency_amount;

    /**
     * @var \DateTime Retention Date
     */
    public $date;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->percent = $data['percent'] ?? null;
        $this->amount = $data['amount'] ?? null;
        $this->currency_amount = $data['currency_amount'] ?? null;
        $this->date = $data['date'] ?? null;
    }

}
