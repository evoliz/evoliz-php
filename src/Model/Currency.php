<?php

namespace Evoliz\Client\Model;

class Currency
{
    /**
     * @var string Iso currency code
     */
    public $code;

    /**
     * @var float Conversion rate with the "EUR" currency
     */
    public $conversion;

    /**
     * @var string Currency symbol
     */
    public $symbol;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->code = $data['code'] ?? null;
        $this->conversion = $data['conversion'] ?? null;
        $this->symbol = $data['symbol'] ?? null;
    }

}
