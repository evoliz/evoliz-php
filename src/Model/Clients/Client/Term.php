<?php

namespace Evoliz\Client\Model\Clients\Client;

class Term
{
    /**
     * @var integer Client's quote period of validity (in days)
     */
    public $validity;

    /**
     * @var float Penalty rate, prohibited if no_penalty is true
     */
    public $penalty;

    /**
     * @var float Discount rate, prohibited if no_discount_term is true
     */
    public $discount_term;

    /**
     * @var boolean Use legal mention about penalty rate
     */
    public $no_penalty;

    /**
     * @var boolean Use legal collection cost
     */
    public $recovery_indemnity;

    /**
     * @var boolean No relevant discount rate
     */
    public $no_discount_term;

    /**
     * @var integer Payment term identifier
     */
    public $paytermid;

    /**
     * @var integer Payment type identifier
     */
    public $paytypeid;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->paytermid = $this->mapPayTermId($data);
        $this->paytypeid = $this->mapPayTypeId($data);

        $this->validity = $data['validity'] ?? null;
        $this->penalty = $data['penalty'] ?? null;
        $this->discount_term = $data['discount_term'] ?? null;
        $this->no_penalty = $data['no_penalty'] ?? null;
        $this->recovery_indemnity = $data['recovery_indemnity'] ?? null;
        $this->no_discount_term = $data['no_discount_term'] ?? null;
    }

    private function mapPayTermId(array $data)
    {
        if (isset($data['payterm'])) {
            $paytermid = $data['payterm']->paytermid;
        } elseif (isset($data['paytermid'])) {
            $paytermid = $data['paytermid'];
        }

        return $paytermid ?? null;
    }

    private function mapPayTypeId(array $data)
    {
        if (isset($data['paytype'])) {
            $paytypeid = $data['paytype']->paytypeid;
        } elseif (isset($data['paytypeid'])) {
            $paytypeid = $data['paytypeid'];
        }

        return $paytypeid ?? null;
    }
}