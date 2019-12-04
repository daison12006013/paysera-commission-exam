<?php

namespace Daison\Paysera\Services\Operators;

use Daison\Paysera\Services\Traits\ExchangeSetterTrait;

class CashOut
{
    use ExchangeSetterTrait;

    const COMMISSION_FEE = 0.3;
    const MAX_FEE        = 5.00;
    const LEGAL_MINIMUM  = 0.5;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function fee()
    {
        $type = $this->collection->userType();

        return $this->{'feeFor'.ucfirst($type)}();
    }

    protected function feeForNatural()
    {
        return bcmul(
            $this->collection->amount(),
            bcdiv(static::COMMISSION_FEE, 100, 30),
            2
        );
    }

    protected function feeForLegal()
    {
        $operationAmount = $this->exchange->convert(
            $this->collection->currency(),
            $this->collection->amount()
        );

        if ($operationAmount <= static::LEGAL_MINIMUM) {
            return 0;
        }

        return $this->feeForNatural();
    }
}
