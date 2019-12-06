<?php

namespace Daison\Paysera\Tests\Services\Operators;

use PHPUnit\Framework\TestCase;
use Daison\Paysera\Transformers\Collection;
use Daison\Paysera\Services\Operators\CashIn;

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

        // amount * (% commission fee / 100%)
        // 1200 * (0.3% / 100%)
        // 1200 * 0.003
        // should be 3.6
        $this->assertEquals($instance->fee(), '3.60');
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

        // based on the specs, a maximum of 5.00 EUR to pay
        $this->assertEquals($instance->fee(), '5.00');
    }
}
