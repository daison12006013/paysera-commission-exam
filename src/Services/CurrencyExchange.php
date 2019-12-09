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
    /**
     * Override this method if you want to use API to
     * fetch latest exchange rate!
     */
    public function getLatestExchange(string $currency): array
    {
        $exchange = [
            'EUR' => '1',
            'USD' => '1.1497',
            'JPY' => '129.53',
        ];

        if (!isset($exchange[$currency])) {
            throw new Exception("We currently don't support [$currency] this type of currency.");
        }

        return $exchange;
    }

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
        $latestExchange = $this->getLatestExchange($currency);

        return Math::div(
            (string) $value,
            $latestExchange[$currency]
        );
    }

    public function convertBack($currency, $value)
    {
        $latestExchange = $this->getLatestExchange($currency);

        return Math::mul(
            (string) $value,
            $latestExchange[$currency]
        );
    }
}
