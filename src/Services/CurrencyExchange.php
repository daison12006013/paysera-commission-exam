<?php

namespace Daison\Paysera\Services;

use Exception;
use Daison\Paysera\Contracts\ShouldConvertCurrencies;

class CurrencyExchange implements ShouldConvertCurrencies
{
    const EXCHANGE = [
        'EUR' => 1,
        'USD' => 1.1497,
        'JPY' => 129.53,
    ];

    public function convert($currency, $value): string
    {
        if (!isset(static::EXCHANGE[$currency])) {
            throw new Exception("We currently don't support [$currency] this type of currency.");
        }

        return bcdiv($value, static::EXCHANGE[$currency], 2);
    }
}
