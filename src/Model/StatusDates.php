<?php

namespace Evoliz\Client\Model;

class StatusDates
{
    /**
     * @var \DateTime
     */
    private $create = null;

    /**
     * @var \DateTime
     */
    private $sent = null;

    /**
     * @var \DateTime
     */
    private $inpayment = null;

    /**
     * @var \DateTime
     */
    private $paid = null;

    /**
     * @var \DateTime
     */
    private $match = null;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->create = isset($data['create']) ? $data['create'] : null;
        $this->sent = isset($data['sent']) ? $data['sent'] : null;
        $this->inpayment = isset($data['inpayment']) ? $data['inpayment'] : null;
        $this->paid = isset($data['paid']) ? $data['paid'] : null;
        $this->match = isset($data['match']) ? $data['match'] : null;
    }

    /**
     * @return \DateTime
     */
    public function getCreate()
    {
        return $this->create;
    }

    /**
     * @param \DateTime $create
     */
    public function setCreate($create)
    {
        $this->create = $create;
    }

    /**
     * @return \DateTime
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * @param \DateTime $sent
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    }

    /**
     * @return \DateTime
     */
    public function getInpayment()
    {
        return $this->inpayment;
    }

    /**
     * @param \DateTime $inpayment
     */
    public function setInpayment($inpayment)
    {
        $this->inpayment = $inpayment;
    }

    /**
     * @return \DateTime
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param \DateTime $paid
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }

    /**
     * @return \DateTime
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param \DateTime $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }

}