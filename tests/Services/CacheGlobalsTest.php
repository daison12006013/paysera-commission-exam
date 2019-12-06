<?php

namespace Daison\Paysera\Tests\Services;

use Daison\Paysera\Services\CacheGlobals as Cache;
use PHPUnit\Framework\TestCase;

class CacheGlobalsTest extends TestCase
{
    public function testScenario()
    {
        $cache = Cache::getInstance();

        $cache->put($key = 'my-random-key', $str = 'yes it works!');

        $this->assertTrue($cache->has($key));
        $this->assertEquals($cache->get($key), $str);

        $cache->purge($key);
        $this->assertFalse($cache->has($key));
    }
}
