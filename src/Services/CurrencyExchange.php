<?php

declare(strict_types=1);

namespace Daison\Paysera\Services;

use Daison\Paysera\Contracts\ShouldConvertCurrencies;
use Exception;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
class CurrencyExchange implements ShouldConvertCurrencies
{
    const EXCHANGE = [
        'EUR' => '1',
        'USD' => '1.1497',
        'JPY' => '129.53',
    ];

    /**
     * Undocumented function.
     *
     * @param string $currency
     * @param mixed  $value
     *
     * @return string
     */
    public function convert($currency, $value)
    {
        if (!isset(static::EXCHANGE[$currency])) {
            throw new Exception("We currently don't support [$currency] this type of currency.");
        }

        return Math::div(
            (string) $value,
            static::EXCHANGE[$currency],
            2
        );
    }
}
