<?php

namespace Evoliz\Client\Model;

class ItemTotal
{
    /**
     * @var Rebate Document amount rebate
     */
    public $rebate;

    /**
     * @var float Total amount of the document excluding vat in currency
     */
    public $vat_exclude;

    /**
     * @var float Total amount of vat in currency
     */
    public $vat;

    /**
     * @var float Total amount of the document including vat in currency
     */
    public $vat_include;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->rebate = isset($data['rebate']) ? new Rebate($data['rebate']) : null;
        $this->vat_exclude = $data['vat_exclude'] ?? null;
        $this->vat = $data['vat'] ?? null;
        $this->vat_include = $data['vat_include'] ?? null;
    }

}
