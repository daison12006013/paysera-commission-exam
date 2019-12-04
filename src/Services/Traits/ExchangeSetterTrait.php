<?php

namespace Daison\Paysera\Services\Traits;

use Daison\Paysera\Contracts\ShouldConvertCurrencies;

trait ExchangeSetterTrait
{
    protected $exchange;

    public function setCurrencyExchange(ShouldConvertCurrencies $exchange)
    {
        $this->exchange = $exchange;

        return $this;
    }
}
