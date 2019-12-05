<?php

declare(strict_types=1);

namespace Daison\Paysera\Services;

use Daison\Paysera\Contracts\ShouldCacheable;
use Daison\Paysera\Traits\MakeableTrait;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
class CacheGlobals implements ShouldCacheable
{
    use MakeableTrait;

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return void
     */
    public function purge($key)
    {
        unset($GLOBALS[$key]);
    }

    /**
     * Undocumented function.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function put($key, $value)
    {
        $GLOBALS[$key] = $value;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($GLOBALS[$key]);
    }

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $GLOBALS[$key];
    }
}
