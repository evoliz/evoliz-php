<?php

namespace Evoliz\Client\Response\Client;

use Evoliz\Client\Response\Common\PayTermResponse;
use Evoliz\Client\Response\Common\PayTypeResponse;

class TermResponse
{
    /**
     * @var integer Client's quote period of validity (in days)
     */
    public $validity;

    /**
     * @var PayTermResponse Payment condition term
     */
    public $payterm;

    /**
     * @var PayTypeResponse Payment condition term
     */
    public $paytype;

    /**
     * @var float Penalty rate
     */
    public $penalty;

    /**
     * @var boolean Use legal mention about penalty rate
     */
    public $no_penalty;

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
     * @param array $data response array to build the object
     */
    public function __construct(array $data)
    {
        $this->validity = $data['validity'] ?? null;
        $this->payterm = isset($data['payterm']) ? new PayTermResponse($data['payterm']) : null;
        $this->paytype = isset($data['paytype']) ? new PayTypeResponse($data['paytype']) : null;
        $this->penalty = $data['penalty'] ?? null;
        $this->no_penalty = $data['no_penalty'] ?? null;
        $this->recovery_indemnity = $data['recovery_indemnity'] ?? null;
        $this->discount_term = $data['discount_term'] ?? null;
        $this->no_discount_term = $data['no_discount_term'] ?? null;
    }


}
