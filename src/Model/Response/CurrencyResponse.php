<?php

namespace Evoliz\Client\Model\Response;

class CurrencyResponse
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
     * @var string CurrencyResponse symbol
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
