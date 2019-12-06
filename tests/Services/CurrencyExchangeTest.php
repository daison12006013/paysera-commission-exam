<?php

namespace Daison\Paysera\Tests\Services;

use Daison\Paysera\Services\CurrencyExchange;
use PHPUnit\Framework\TestCase;

class CurrencyExchangeTest extends TestCase
{
    public function setUp()
    {
        $this->exchange = new CurrencyExchange();
    }

    public function testScenario()
    {
        $this->assertEquals($this->exchange->convert('EUR', 1), 1);
        $this->assertEquals($this->exchange->convert('JPY', 129.53), 1);
        $this->assertEquals($this->exchange->convert('USD', 1.1497), 1);

        $this->assertEquals($this->exchange->convert('JPY', 100), 0.77);
        $this->assertEquals($this->exchange->convert('USD', 100), 86.97);
    }

    public function testUnsupportedCurrency()
    {
        try {
            $this->exchange->convert('PHP', 100);

            $this->assertTrue(false);
        } catch (\Throwable $e) {
            $this->assertEquals(
                $e->getMessage(),
                'We currently don\'t support [PHP] this type of currency.'
            );
        }
    }
}
