<?php

namespace Evoliz\Client\Model;

class Invoice
{
    /**
     * @var integer
     */
    private $invoiceid;

    /**
     * @var string
     */
    private $typedoc;

    /**
     * @var string
     */
    private $document_number;

    /**
     * @var integer
     */
    private $userid;

    /**
     * @var LinkedClient
     */
    private $client;

    /**
     * @var Currency
     */
    private $default_currency;

    /**
     * @var Currency
     */
    private $document_currency = null;

    /**
     * @var SellDocTotal
     */
    private $total;

    /**
     * @var SellDocTotal
     */
    private $currency_total = null;

    /**
     * @var integer
     */
    private $status_code;

    /**
     * @var string
     */
    private $status;

    /**
     * @var StatusDates
     */
    private $status_dates;

    /**
     * @var boolean
     */
    private $locked = null;

    /**
     * @var \DateTime
     */
    private $lockdate = null;

    /**
     * @var string
     */
    private $object;

    /**
     * @var \DateTime
     */
    private $documentdate;

    /**
     * @var \DateTime
     */
    private $duedate;

    /**
     * @var \DateTime
     */
    private $execdate;

    /**
     * @var Term
     */
    private $term;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $comment_clean;

    /**
     * @var string
     */
    private $external_document_number;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var Analytic
     */
    private $analytic = null;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $links;

    /**
     * @var string
     */
    private $webdoc = null;

    /**
     * @var integer
     */
    private $recovery_number;

    /**
     * @var Retention
     */
    private $retention = null;

    /**
     * @var array of SellDocItems
     */
    private $items = [];

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->invoiceid = $data['invoiceid'] ?? null;
        $this->typedoc = $data['typedoc'] ?? null;
        $this->document_number = $data['document_number'] ?? null;
        $this->userid = $data['userid'] ?? null;
        $this->client = isset($data['client']) ? new LinkedClient($data['client']) : null;
        $this->default_currency = isset($data['default_currency']) ? new Currency($data['default_currency']) : null;
        $this->document_currency = isset($data['document_currency']) ? new Currency($data['document_currency']) : null;
        $this->total = isset($data['total']) ? new SellDocTotal($data['total']) : null;
        $this->currency_total = isset($data['currency_total']) ? new SellDocTotal($data['currency_total']) : null;
        $this->status_code = $data['status_code'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->status_dates = isset($data['status_dates']) ? new StatusDates($data['status_dates']) : null;
        $this->locked = $data['locked'] ?? null;
        $this->lockdate = $data['lockdate'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->documentdate = $data['documentdate'] ?? null;
        $this->duedate = $data['duedate'] ?? null;
        $this->execdate = $data['execdate'] ?? null;
        $this->term = isset($data['term']) ? new Term($data['term']) : null;
        $this->comment = $data['comment'] ?? null;
        $this->comment_clean = $data['comment_clean'] ?? null;
        $this->external_document_number = $data['external_document_number'] ?? null;
        $this->enabled = $data['enabled'] ?? null;
        $this->analytic = isset($data['analytic']) ? new Analytic($data['analytic']) : null;
        $this->file = $data['file'] ?? null;
        $this->links = $data['links'] ?? null;
        $this->webdoc = $data['webdoc'] ?? null;
        $this->recovery_number = $data['recovery_number'] ?? null;
        $this->retention = isset($data['retention']) ? new Retention($data['retention']) : null;

        if (isset($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $this->items[] = new SellDocItem($itemData);
            }
        }
    }

    /**
     * @return int
     */
    public function getInvoiceId()
    {
        return $this->invoiceid;
    }

    /**
     * @param int $invoiceid
     */
    public function setInvoiceId(int $invoiceid)
    {
        $this->invoiceid = $invoiceid;
    }

    /**
     * @return string
     */
    public function getTypedoc()
    {
        return $this->typedoc;
    }

    /**
     * @param string $typedoc
     */
    public function setTypedoc(string $typedoc)
    {
        $this->typedoc = $typedoc;
    }

    /**
     * @return string
     */
    public function getDocumentNumber()
    {
        return $this->document_number;
    }

    /**
     * @param string $document_number
     */
    public function setDocumentNumber(string $document_number)
    {
        $this->document_number = $document_number;
    }

    /**
     * @return integer
     */
    public function getUserId()
    {
        return $this->userid;
    }

    /**
     * @param integer $userid
     */
    public function setUserId(int $userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return LinkedClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param LinkedClient $client
     */
    public function setClient(LinkedClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return Currency
     */
    public function getDefaultCurrency()
    {
        return $this->default_currency;
    }

    /**
     * @param Currency $default_currency
     */
    public function setDefaultCurrency(Currency $default_currency)
    {
        $this->default_currency = $default_currency;
    }

    /**
     * @return Currency
     */
    public function getDocumentCurrency()
    {
        return $this->document_currency;
    }

    /**
     * @param Currency $document_currency
     */
    public function setDocumentCurrency(Currency $document_currency)
    {
        $this->document_currency = $document_currency;
    }

    /**
     * @return SellDocTotal
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param SellDocTotal $total
     */
    public function setTotal(SellDocTotal $total)
    {
        $this->total = $total;
    }

    /**
     * @return SellDocTotal
     */
    public function getCurrencyTotal()
    {
        return $this->currency_total;
    }

    /**
     * @param SellDocTotal $currency_total
     */
    public function setCurrencyTotal(SellDocTotal $currency_total)
    {
        $this->currency_total = $currency_total;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param int $status_code
     */
    public function setStatusCode(int $status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return StatusDates
     */
    public function getStatusDates()
    {
        return $this->status_dates;
    }

    /**
     * @param StatusDates $status_dates
     */
    public function setStatusDates(StatusDates $status_dates)
    {
        $this->status_dates = $status_dates;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     */
    public function setLocked(bool $locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return \DateTime
     */
    public function getLockdate()
    {
        return $this->lockdate;
    }

    /**
     * @param \DateTime $lockdate
     */
    public function setLockdate(\DateTime $lockdate)
    {
        $this->lockdate = $lockdate;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $object
     */
    public function setObject(string $object)
    {
        $this->object = $object;
    }

    /**
     * @return \DateTime
     */
    public function getDocumentdate()
    {
        return $this->documentdate;
    }

    /**
     * @param \DateTime $documentdate
     */
    public function setDocumentdate(\DateTime $documentdate)
    {
        $this->documentdate = $documentdate;
    }

    /**
     * @return \DateTime
     */
    public function getDuedate()
    {
        return $this->duedate;
    }

    /**
     * @param \DateTime $duedate
     */
    public function setDuedate(\DateTime $duedate)
    {
        $this->duedate = $duedate;
    }

    /**
     * @return \DateTime
     */
    public function getExecdate()
    {
        return $this->execdate;
    }

    /**
     * @param \DateTime $execdate
     */
    public function setExecdate(\DateTime $execdate)
    {
        $this->execdate = $execdate;
    }

    /**
     * @return Term
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param Term $term
     */
    public function setTerm(Term $term)
    {
        $this->term = $term;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getCommentClean()
    {
        return $this->comment_clean;
    }

    /**
     * @param string $comment_clean
     */
    public function setCommentClean(string $comment_clean)
    {
        $this->comment_clean = $comment_clean;
    }

    /**
     * @return string
     */
    public function getExternalDocumentNumber()
    {
        return $this->external_document_number;
    }

    /**
     * @param string $external_document_number
     */
    public function setExternalDocumentNumber(string $external_document_number)
    {
        $this->external_document_number = $external_document_number;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return Analytic
     */
    public function getAnalytic()
    {
        return $this->analytic;
    }

    /**
     * @param Analytic $analytic
     */
    public function setAnalytic(Analytic $analytic)
    {
        $this->analytic = $analytic;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile(string $file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param string $links
     */
    public function setLinks(string $links)
    {
        $this->links = $links;
    }

    /**
     * @return string
     */
    public function getWebdoc()
    {
        return $this->webdoc;
    }

    /**
     * @param string $webdoc
     */
    public function setWebdoc(string $webdoc)
    {
        $this->webdoc = $webdoc;
    }

    /**
     * @return int
     */
    public function getRecoveryNumber()
    {
        return $this->recovery_number;
    }

    /**
     * @param int $recovery_number
     */
    public function setRecoveryNumber(int $recovery_number)
    {
        $this->recovery_number = $recovery_number;
    }

    /**
     * @return Retention
     */
    public function getRetention()
    {
        return $this->retention;
    }

    /**
     * @param Retention $retention
     */
    public function setRetention(Retention $retention)
    {
        $this->retention = $retention;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }


}
