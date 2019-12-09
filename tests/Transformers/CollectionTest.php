<?php

namespace Daison\Paysera\Tests\Transformers;

use PHPUnit\Framework\TestCase;
use Daison\Paysera\Transformers\Collection;

class CollectionTest extends TestCase
{
    public function setUp()
    {
        $this->collection = new Collection([
            '2019-01-01',
            4,
            'natural',
            'cash_in',
            1200.00,
            'EUR',
        ]);
    }

    public function testAccessorAndMutation()
    {
        $this->collection->setValue($k = 'myKey', $v = '1234qwerASDF!@#$');

        $this->assertEquals($this->collection->getValue($k), $v);

        $this->assertEquals(
            $this->collection->getValue('yourKey'),
            null
        );
    }

    public function testMagicMethodCall()
    {
        $this->assertNull($this->collection->methodDoesNotExists());
    }
}
