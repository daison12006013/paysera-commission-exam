<?php

declare(strict_types=1);

namespace Daison\Paysera\Contracts;

interface ShouldCacheable
{
    public function purge($key);

    public function put($key, $value);

    public function has($key);

    public function get($key);
}
