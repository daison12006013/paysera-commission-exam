<?php

namespace Daison\Paysera\Services\Operators;

use Daison\Paysera\Services\Traits\ExchangeSetterTrait;

class CashIn
{
    use ExchangeSetterTrait;

    const COMMISSION_FEE = 0.3;
    const MAX_FEE        = 5.00;

    private $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function fee()
    {
        $fee = bcmul(
            $this->collection->amount(),
            bcdiv(static::COMMISSION_FEE, 100, 30),
            2
        );

        if ($fee >= static::MAX_FEE) {
            return bcadd(static::MAX_FEE, 0, 2);
        }

        return $fee;
    }
}
