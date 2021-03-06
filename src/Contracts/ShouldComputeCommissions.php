<?php

declare(strict_types=1);

namespace Daison\Paysera\Contracts;

use Daison\Paysera\Transformers\Collection;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
interface ShouldComputeCommissions
{
    /**
     * Undocumented function.
     *
     * @return string|float
     */
    public function compute(Collection $collection);
}
