<?php

namespace Daison\Paysera\Tests\Services\Operators;

use PHPUnit\Framework\TestCase;
use Daison\Paysera\Transformers\Collection;
use Daison\Paysera\Services\Operators\CashIn;
use Daison\Paysera\Services\CurrencyExchange;

class CashInTest extends TestCase
{
    public function testNormalFee()
    {
        $instance = new CashIn(new Collection([
            '2019-01-01',
            4,
            'natural',
            'cash_in',
            1200.00,
            'EUR',
        ]));
        $instance->setCurrencyExchange(new CurrencyExchange());

        // amount * (% commission fee / 100%)
        // 1200 * (0.03% / 100%)
        // 1200 * 0.0003
        // should be 0.36
        $this->assertEquals($instance->fee(), '0.36');
    }

    public function testMaxFee()
    {
        $instance = new CashIn(new Collection([
            '2019-01-01',
            4,
            'natural',
            'cash_in',
            1000000,
            'EUR',
        ]));
        $instance->setCurrencyExchange(new CurrencyExchange());

        // based on the specs, a maximum of 5.00 EUR to pay
        $this->assertEquals($instance->fee(), '5.00');
    }
}
