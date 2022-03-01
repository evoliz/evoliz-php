<?php

namespace Evoliz\Client\Model;

class Term
{
    /**
     * @var float
     */
    private $penalty;

    /**
     * @var boolean
     */
    private $nopenalty;

    /**
     * @var boolean
     */
    private $recovery_indemnity;

    /**
     * @var float
     */
    private $discount_term;

    /**
     * @var boolean
     */
    private $no_discount_term;

    /**
     * @var PayTerm
     */
    private $payterm;

    /**
     * @var PayType
     */
    private $paytype;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->penalty = isset($data['penalty']) ? $data['penalty'] : null;
        $this->nopenalty = isset($data['nopenalty']) ? $data['nopenalty'] : null;
        $this->recovery_indemnity = isset($data['recovery_indemnity']) ? $data['recovery_indemnity'] : null;
        $this->discount_term = isset($data['discount_term']) ? $data['discount_term'] : null;
        $this->no_discount_term = isset($data['no_discount_term']) ? $data['no_discount_term'] : null;
        $this->payterm = isset($data['payterm']) ? new PayTerm($data['payterm']) : null;
        $this->paytype = isset($data['paytype']) ? new PayType($data['paytype']) : null;
    }

    /**
     * @return float
     */
    public function getPenalty()
    {
        return $this->penalty;
    }

    /**
     * @param float $penalty
     */
    public function setPenalty($penalty)
    {
        $this->penalty = $penalty;
    }

    /**
     * @return bool
     */
    public function isNoPenalty()
    {
        return $this->nopenalty;
    }

    /**
     * @param bool $nopenalty
     */
    public function setNoPenalty($nopenalty)
    {
        $this->nopenalty = $nopenalty;
    }

    /**
     * @return bool
     */
    public function isRecoveryIndemnity()
    {
        return $this->recovery_indemnity;
    }

    /**
     * @param bool $recovery_indemnity
     */
    public function setRecoveryIndemnity($recovery_indemnity)
    {
        $this->recovery_indemnity = $recovery_indemnity;
    }

    /**
     * @return float
     */
    public function getDiscountTerm()
    {
        return $this->discount_term;
    }

    /**
     * @param float $discount_term
     */
    public function setDiscountTerm($discount_term)
    {
        $this->discount_term = $discount_term;
    }

    /**
     * @return bool
     */
    public function isNoDiscountTerm()
    {
        return $this->no_discount_term;
    }

    /**
     * @param bool $no_discount_term
     */
    public function setNoDiscountTerm($no_discount_term)
    {
        $this->no_discount_term = $no_discount_term;
    }

    /**
     * @return PayTerm
     */
    public function getPayTerm()
    {
        return $this->payterm;
    }

    /**
     * @param PayTerm $payterm
     */
    public function setPayTerm($payterm)
    {
        $this->payterm = $payterm;
    }

    /**
     * @return PayType
     */
    public function getPayType()
    {
        return $this->paytype;
    }

    /**
     * @param PayType $paytype
     */
    public function setPayType($paytype)
    {
        $this->paytype = $paytype;
    }

}