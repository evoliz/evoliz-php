<?php

namespace Evoliz\Client\Model;

class Currency
{

    /**
     * @var string
     */
    private $code;

    /**
     * @var float
     */
    private $conversion;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->code = $data['code'] ?? null;
        $this->conversion = $data['conversion'] ?? null;
        $this->symbol = $data['symbol'] ?? null;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return float
     */
    public function getConversion()
    {
        return $this->conversion;
    }

    /**
     * @param float $conversion
     */
    public function setConversion($conversion)
    {
        $this->conversion = $conversion;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }
}
