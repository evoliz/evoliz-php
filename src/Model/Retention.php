<?php

namespace Evoliz\Client\Model;

class Retention
{
    /**
     * @var float
     */
    private $percent;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $currency_amount;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->percent = isset($data['percent']) ? $data['percent'] : null;
        $this->amount = isset($data['amount']) ? $data['amount'] : null;
        $this->currency_amount = isset($data['currency_amount']) ? $data['currency_amount'] : null;
        $this->date = isset($data['date']) ? $data['date'] : null;
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getCurrencyAmount()
    {
        return $this->currency_amount;
    }

    /**
     * @param float $currency_amount
     */
    public function setCurrencyAmount($currency_amount)
    {
        $this->currency_amount = $currency_amount;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}