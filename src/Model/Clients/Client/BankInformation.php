<?php

namespace Evoliz\Client\Model\Clients\Client;

class BankInformation
{
    /**
     * @var string Bank name
     */
    public $bank_name;

    /**
     * @var string Bank account details
     */
    public $bank_account_detail;

    /**
     * @var string International Bank Account Number
     */
    public $iban;

    /**
     * @var string Bank Identifier Code (BIC, SWIFT)
     */
    public $bank_identification_code;

    /**
     * @param array $data Array to build the object
     */
    public function __construct(array $data)
    {
        $this->bank_name = $data['bank_name'] ?? null;
        $this->bank_account_detail = $data['bank_account_detail'] ?? null;
        $this->iban = $data['iban'] ?? null;
        $this->bank_identification_code = $data['bank_identification_code'] ?? null;
    }


}
