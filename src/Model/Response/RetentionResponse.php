<?php

namespace Evoliz\Client\Model\Response;

class RetentionResponse
{
    /**
     * @var float RetentionResponse percent
     */
    public $percent;

    /**
     * @var float RetentionResponse amount
     */
    public $amount;

    /**
     * @var float RetentionResponse amount in currency
     */
    public $currency_amount;

    /**
     * @var \DateTime RetentionResponse Date
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
