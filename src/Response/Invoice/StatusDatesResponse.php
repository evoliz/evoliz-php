<?php

namespace Evoliz\Client\Response\Invoice;

class StatusDatesResponse
{
    /**
     * @var \DateTime Document emission date
     */
    public $create = null;

    /**
     * @var \DateTime Document sent date
     */
    public $sent = null;

    /**
     * @var \DateTime Document partially paid date
     */
    public $inpayment = null;

    /**
     * @var \DateTime Document paid date
     */
    public $paid = null;

    /**
     * @var \DateTime Document match date
     */
    public $match = null;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->create = $data['create'] ?? null;
        $this->sent = $data['sent'] ?? null;
        $this->inpayment = $data['inpayment'] ?? null;
        $this->paid = $data['paid'] ?? null;
        $this->match = $data['match'] ?? null;
    }

}
