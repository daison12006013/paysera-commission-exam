<?php

namespace Daison\Paysera\Contracts;

interface ShouldConvertCurrencies
{
    public function convert($currency, $value): string;
}
