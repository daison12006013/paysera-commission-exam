<?php

namespace Daison\Paysera\Tests\Services\Operators;

use Daison\Paysera\Services\CurrencyExchange;
use Daison\Paysera\Services\Operators\CashOut;
use Daison\Paysera\Transformers\Collection;
use PHPUnit\Framework\TestCase;

class CashOutTest extends TestCase
{
    private function createInstance($datum)
    {
        $instance = new CashOut(new Collection($datum));

        $instance->setCurrencyExchange(new CurrencyExchange());

        return $instance;
    }

    public function testLegalPerson()
    {
        $instance = $this->createInstance([
            '2019-01-01',
            4,
            'legal',
            'cash_out',
            5000.00,
            'EUR',
        ]);

        // amount * (% commission fee / 100%)
        // 5000 * (0.3% / 100%)
        // 5000 * 0.003
        // should be 15
        $this->assertEquals($instance->fee(), '15.00');
    }

    public function testLegalPersonWithLegalLimit()
    {
        $instance = $this->createInstance([
            '2019-01-01',
            2,
            'legal',
            'cash_out',
            60,
            'JPY',
        ]);

        // JPY of 60 if we will convert it to EUR
        // 129.53 jpy = 1eur
        // 60/129.53 is just a 0.46 eur
        // based on the specs, no commission fee
        // for legal limit of 0.5 eur
        $this->assertEquals($instance->fee(), '0.00');
    }

    public function testNaturalPerson()
    {
        $userId = 999;
        $records = [
            [
                'collection' => ['2019-12-02', $userId, 'natural', 'cash_out', 300, 'EUR'],
                'result'     => '0.00',
            ],
            [
                'collection' => ['2019-12-02', $userId, 'natural', 'cash_out', 300, 'EUR'],
                'result'     => '0.00',
            ],
            [
                'collection' => ['2019-12-03', $userId, 'natural', 'cash_out', 300, 'EUR'],
                'result'     => '0.00',
            ],
            [
                // 1000 allocated free of the week
                'collection' => ['2019-12-08', $userId, 'natural', 'cash_out', 100, 'EUR'],
                'result'     => '0.00',
            ],
            [
                'collection' => ['2019-12-08', $userId, 'natural', 'cash_out', 100, 'EUR'],
                'result'     => '0.30',
            ],
            [
                'collection' => ['2019-12-08', $userId, 'natural', 'cash_out', 300, 'EUR'],
                'result'     => '0.90',
            ],

            // this is Monday, different week
            // the user cashed out for 1100
            // so the result must be "0.30" commission fee only
            [
                'collection' => ['2019-12-09', $userId, 'natural', 'cash_out', 1100, 'EUR'],
                'result'     => '0.30',
            ],
        ];

        foreach ($records as $record) {
            $instance = $this->createInstance($record['collection']);

            $this->assertEquals($instance->fee(), $record['result']);
        }
    }
}
