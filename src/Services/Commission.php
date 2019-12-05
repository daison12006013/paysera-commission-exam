<?php

declare(strict_types=1);

namespace Daison\Paysera\Services;

use Daison\Paysera\Contracts\ShouldComputeCommissions;
use Daison\Paysera\Traits\ExchangeSetterTrait;
use Daison\Paysera\Transformers\Collection;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
class Commission implements ShouldComputeCommissions
{
    use ExchangeSetterTrait;

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $operators = [
        'cash_in'  => Operators\CashIn::class,
        'cash_out' => Operators\CashOut::class,
    ];

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    public function compute(Collection $collection)
    {
        $class    = $this->operators[$collection->operationType()];
        $operator = new $class($collection);
        $operator->setCurrencyExchange($this->exchange);
        $amount = $operator->fee();

        $collection->setValue('rawFee', $amount);
        $collection->setValue('convertedFee', $this->exchange->convert(
            $collection->currency(),
            $amount
        ));

        return $amount;
    }
}
