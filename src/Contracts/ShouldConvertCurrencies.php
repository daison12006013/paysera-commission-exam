<?php

declare(strict_types=1);

namespace Daison\Paysera\Contracts;

interface ShouldConvertCurrencies
{
    public function convert($currency, $value): string;
}
