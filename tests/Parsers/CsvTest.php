<?php

namespace Daison\Paysera\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use Daison\Paysera\Parsers\Csv;
use Daison\Paysera\Transformers\Collection;
use Daison\Paysera\Exceptions\WrongFileExtension;

class CsvTest extends TestCase
{
    public function testCsvCollection()
    {
        $arr = new Csv(__DIR__.'/../input.csv');

        $collections = $arr->collections();

        $this->assertInstanceOf(Collection::class, $collections[0]);
        $this->assertEquals($collections[0]->date(), '2014-12-31');
        $this->assertEquals($collections[0]->userId(), '4');
        $this->assertEquals($collections[0]->userType(), 'natural');
        $this->assertEquals($collections[0]->operationType(), 'cash_out');
        $this->assertEquals($collections[0]->amount(), '1200.00');
        $this->assertEquals($collections[0]->currency(), 'EUR');
    }

    public function testIsCsv()
    {
        try {
            $arr = new Csv(__DIR__.'/../sample.txt');
        } catch (\Throwable $e) {
            $this->assertEquals(
                $e->getMessage(),
                'Expected extension must be [.csv] format'
            );

            $this->assertInstanceOf(WrongFileExtension::class, $e);
        }
    }
}
