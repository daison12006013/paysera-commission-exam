<?php

namespace Daison\Paysera\Tests;

use Daison\Paysera\Application;
use Daison\Paysera\Parsers\Csv;
use PHPUnit\Framework\TestCase;
use Daison\Paysera\Transformers\Collection;

class ApplicationTest extends TestCase
{
    public function testBootstrap()
    {
        $collections = Application::make() // or: Manager::getInstance()
            ->setData(new Csv(__DIR__ . '/input.csv'))
            ->handle();

        $this->assertTrue(is_array($collections));
        $this->assertInstanceOf(Collection::class, $collections[0]);
    }
}
