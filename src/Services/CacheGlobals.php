<?php

namespace Daison\Paysera\Services;

use Daison\Paysera\Contracts\ShouldCacheable;

class CacheGlobals implements ShouldCacheable
{
    public static function make()
    {
        return new static();
    }

    public function purge($key)
    {
        unset($GLOBALS[$key]);
    }

    public function put($key, $value)
    {
        $GLOBALS[$key] = $value;

        return $this;
    }

    public function has($key)
    {
        return isset($GLOBALS[$key]);
    }

    public function get($key)
    {
        return $GLOBALS[$key];
    }
}
