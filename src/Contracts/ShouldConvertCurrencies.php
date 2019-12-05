<?php

declare(strict_types=1);

namespace Daison\Paysera\Contracts;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
interface ShouldConvertCurrencies
{
    /**
     * Undocumented function.
     *
     * @param string    $currency
     * @param int|float $value
     *
     * @return string|float
     */
    public function convert($currency, $value);
}
