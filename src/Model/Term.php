<?php

namespace Evoliz\Client\Model;

class Term
{
    /**
     * @var float Penalty rate
     */
    public $penalty;

    /**
     * @var boolean Use legal mention about penalty rate
     */
    public $nopenalty;

    /**
     * @var boolean Use legal collection cost
     */
    public $recovery_indemnity;

    /**
     * @var float Discount rate
     */
    public $discount_term;

    /**
     * @var boolean No relevant discount rate
     */
    public $no_discount_term;

    /**
     * @var PayTerm Payment condition term
     */
    public $payterm;

    /**
     * @var PayType Payment condition term
     */
    public $paytype;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->penalty = $data['penalty'] ?? null;
        $this->nopenalty = $data['nopenalty'] ?? null;
        $this->recovery_indemnity = $data['recovery_indemnity'] ?? null;
        $this->discount_term = $data['discount_term'] ?? null;
        $this->no_discount_term = $data['no_discount_term'] ?? null;
        $this->payterm = isset($data['payterm']) ? new PayTerm($data['payterm']) : null;
        $this->paytype = isset($data['paytype']) ? new PayType($data['paytype']) : null;
    }

}
