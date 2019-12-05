<?php

declare(strict_types=1);

namespace Daison\Paysera\Services;

use Daison\Paysera\Contracts\ShouldConvertCurrencies;
use Exception;

class CurrencyExchange implements ShouldConvertCurrencies
{
    const EXCHANGE = [
        'EUR' => '1',
        'USD' => '1.1497',
        'JPY' => '129.53',
    ];

    public function convert($currency, $value): string
    {
        if (!isset(static::EXCHANGE[$currency])) {
            throw new Exception("We currently don't support [$currency] this type of currency.");
        }

        return bcdiv((string) $value, static::EXCHANGE[$currency], 2);
    }
}
