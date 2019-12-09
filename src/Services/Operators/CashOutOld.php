<?php

declare(strict_types=1);

namespace Daison\Paysera\Services\Operators;

use Daison\Paysera\Services\Math;

/**
 * This is basically used only for Part 1 test.
 *
 * @author Daison Carino <daison12006013@gmail.com>
 */
class CashOutOld extends CashOut
{
    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForNatural()
    {
        // THIS is PART 1's way of calculating natural.
        $amount = $this->collection->amount();

        return Math::mul(
            $amount,
            Math::div(static::COMMISSION_FEE, 100)
        );
    }
}
