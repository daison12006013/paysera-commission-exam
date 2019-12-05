<?php

declare(strict_types=1);

namespace Daison\Paysera\Services\Operators;

use Daison\Paysera\Services\Math;
use Daison\Paysera\Traits\ExchangeSetterTrait;
use Daison\Paysera\Transformers\Collection;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
class CashIn
{
    use ExchangeSetterTrait;

    const COMMISSION_FEE = '0.3';
    const MAX_FEE        = '5.00';

    /**
     * Undocumented variable.
     *
     * @var \Daison\Paysera\Transformers\Collection
     */
    private $collection;

    /**
     * Undocumented function.
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function fee()
    {
        $fee = Math::mul(
            $this->collection->amount(),
            Math::div(static::COMMISSION_FEE, 100),
            2
        );

        if ($fee >= static::MAX_FEE) {
            return Math::add(static::MAX_FEE, 0, 2);
        }

        return $fee;
    }
}
