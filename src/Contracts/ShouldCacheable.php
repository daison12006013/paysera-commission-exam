<?php

declare(strict_types=1);

namespace Daison\Paysera\Contracts;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
interface ShouldCacheable
{
    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return void|bool|self
     */
    public function purge($key);

    /**
     * Undocumented function.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void|bool|self
     */
    public function put($key, $value);

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);
}
