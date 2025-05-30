<?php

namespace Evoliz\Client\Model\Sales;

use Evoliz\Client\Model\BaseModel;

class Term extends BaseModel
{
    /**
     * @var integer Payment term identifier
     */
    public $paytermid;

    /**
     * @var float Penalty rate, prohibited if no_penalty is true
     */
    public $penalty;

    /**
     * @var boolean Define the display of legal mention about penalty rate on the created document
     * Prohibited if a penalty is given
     */
    public $nopenalty;

    /**
     * @var boolean Use legal mention about recovery indemnity
     */
    public $recovery_indemnity;

    /**
     * @var float Discount rate, prohibited if no_discount_term is true
     */
    public $discount_term;

    /**
     * @var boolean Define the display of the no discount applicable legal mention
     * Prohibited if a discount_term is given
     */
    public $no_discount_term;

    /**
     * @var string Payment due date, required if paytermid is 18 (Saisir une date)
     * Must be after or equal to documentdate
     */
    public $duedate;

    /**
     * @var integer Payment delay in days, required if paytermid is 16 (Autre condition)
     */
    public $paydelay;

    /**
     * @var boolean Payment is due at the end of the month, required if paytermid is 16 (Autre condition)
     */
    public $endmonth;

    /**
     * @var integer Payment day, required if paytermid is 16 (Autre condition)
     */
    public $payday;

    /**
     * @var integer Payment type identifier
     */
    public $paytypeid;

    /**
     * @param array $data Array to build the object
     */
    public function __construct(array $data)
    {
        $this->paytermid = $this->extractPaytermId($data);

        $this->penalty = $data['penalty'] ?? null;

        $this->nopenalty = $this->extractNoPenalty($data);

        $this->recovery_indemnity = $data['recovery_indemnity'] ?? null;
        $this->discount_term = $data['discount_term'] ?? null;
        $this->no_discount_term = $data['no_discount_term'] ?? null;
        $this->duedate = $data['duedate'];
        $this->paydelay = $data['paydelay'] ?? null;
        $this->endmonth = $data['endmonth'] ?? null;
        $this->payday = $data['payday'] ?? null;

        $this->paytypeid = $this->extractPaytypeId($data);
    }

    /**
     * Extract the paytermid field with the correct information
     *
     * @param  array $data Array to build the object
     * @return integer|null
     */
    private function extractPaytermId(array $data)
    {
        if (isset($data['payterm'])) {
            $paytermid = $data['payterm']->paytermid;
        } elseif (isset($data['paytermid'])) {
            $paytermid = $data['paytermid'];
        }

        return $paytermid ?? null;
    }

    /**
     * Extract the paytypeid field with the correct information
     *
     * @param  array $data Array to build the object
     * @return integer|null
     */
    private function extractPaytypeId(array $data)
    {
        if (isset($data['paytype'])) {
            $paytypeid = $data['paytype']->paytypeid;
        } elseif (isset($data['paytypeid'])) {
            $paytypeid = $data['paytypeid'];
        }

        return $paytypeid ?? null;
    }

    /**
     * Extract the no_penalty field with the correct information
     *
     * @param  array $data Array to build the object
     * @return integer|null
     */
    private function extractNoPenalty(array $data)
    {
        return $data['nopenalty'] ?? $data['no_penalty'] ?? null;
    }
}
