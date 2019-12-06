<?php

namespace Daison\Paysera\Tests\Services;

use Daison\Paysera\Services\Commission;
use PHPUnit\Framework\TestCase;
use Daison\Paysera\Transformers\Collection;
use Daison\Paysera\Services\CurrencyExchange;

class CommissionTest extends TestCase
{
    public function testScenario()
    {
        $commission = new Commission();
        $commission->setCurrencyExchange(new CurrencyExchange());

        $amount = $commission->compute(new Collection([
            '2019-12-09',
            $userId = 1000,
            'legal',
            'cash_in',
            500,
            'EUR'
        ]));
        $this->assertEquals($amount, '1.50');

        $amount = $commission->compute(new Collection([
            '2019-12-09',
            $userId = 1000,
            'legal',
            'cash_out',
            300,
            'EUR'
        ]));
        $this->assertEquals($amount, '0.90');

        $amount = $commission->compute(new Collection([
            '2019-12-09',
            $userId = 1000,
            'natural',
            'cash_out',
            1100,
            'EUR'
        ]));
        $this->assertEquals($amount, '0.30');
    }
}
