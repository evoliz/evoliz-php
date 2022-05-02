<?php

namespace Evoliz\Client\Response\Sales\SaleOrder;

class StatusDatesResponse
{
    /**
     * @var string Document emission date
     */
    public $create = null;

    /**
     * @var string Document sent date;
     */
    public $sent = null;

    /**
     * @var string Document accept date;
     */
    public $accept = null;

    /**
     * @var string Date when document has been set on waiting state
     */
    public $wait = null;

    /**
     * @var string Document reject date
     */
    public $reject = null;

    /**
     * @var string Document pack date
     */
    public $pack = null;

    /**
     * @var string Document invoice date
     */
    public $invoice = null;

    /**
     * @var string Document close date
     */
    public $close = null;

    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->create = $data['create'] ?? null;
        $this->sent = $data['sent'] ?? null;
        $this->accept = $data['accept'] ?? null;
        $this->wait = $data['wait'] ?? null;
        $this->reject = $data['reject'] ?? null;
        $this->pack = $data['pack'] ?? null;
        $this->invoice = $data['invoice'] ?? null;
        $this->close = $data['close'] ?? null;
    }
}
