<?php

declare(strict_types=1);

namespace Daison\Paysera\Services\Operators;

use Daison\Paysera\Services\CacheGlobals as Cache;
use Daison\Paysera\Services\Math;
use Daison\Paysera\Traits\ExchangeSetterTrait;
use Daison\Paysera\Transformers\Collection;
use DateTime;

/**
 * This is used for Part 2.
 *
 * @author Daison Carino <daison12006013@gmail.com>
 */
class CashOut
{
    use ExchangeSetterTrait;

    const COMMISSION_FEE           = '0.3';
    const MAX_FEE                  = '5.00';
    const LEGAL_MINIMUM            = '0.5';
    const NATURAL_FREE_PER_WEEK    = '1000';
    const NATURAL_FREE_MAX_CASHOUT = 3;

    /**
     * Undocumented function.
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
        $this->cache      = Cache::make();
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function fee()
    {
        $type = $this->collection->userType();

        return Math::add(0, $this->{'feeFor'.ucfirst($type)}());
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForNatural()
    {
        $amount = $this->analyzeNaturalAmount();

        return Math::mul(
            $amount,
            Math::div(static::COMMISSION_FEE, 100),
        );
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForLegal()
    {
        $operationAmount = $this->exchange->convert(
            $this->collection->currency(),
            $this->collection->amount()
        );

        if ($operationAmount <= static::LEGAL_MINIMUM) {
            return 0;
        }

        return Math::mul(
            $this->collection->amount(),
            Math::div(static::COMMISSION_FEE, 100),
        );
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    protected function analyzeNaturalAmount()
    {
        $this->incrementCashOutAttempt();

        $allocated = $this->getUserAllocatedFreeWeek();

        $operationAmount = $this->exchange->convert(
            $this->collection->currency(),
            $this->collection->amount()
        );

        // we need to know the allocated + the collection's amount
        // we will call it as our base value for now...
        $basis = Math::add($allocated, $operationAmount);

        // this condition, where we return 0, meaning that
        // the purchases still under the free week quota
        // thus, we shall return 0 instead.
        if (
            $this->isCashOutFreeWeek($basis)
            && $this->stillInMinimumCashOut()
        ) {
            $this->updateUserAllocatedFreeWeek(
                Math::add($allocated, $operationAmount)
            );

            return '0.00';
        }

        // this is where we determine if our basis is greater than
        // the quota, thus, we need to pre-calculate the value that
        // we need to deduct from the remaining quota it has
        $remaining = Math::sub(
            static::NATURAL_FREE_PER_WEEK,
            $allocated
        );

        $this->updateUserAllocatedFreeWeek(
            Math::add($allocated, $remaining)
        );

        $amount = abs(Math::sub($operationAmount, $remaining));

        return $this->exchange->convertBack(
            $this->collection->currency(),
            $amount
        );
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    protected function getUserAllocatedFreeWeek()
    {
        $key = sprintf('%s-allocated', $this->generateTagKey());

        if (!$this->cache->has($key)) {
            $this->cache->put($key, 0);
        }

        return $this->cache->get($key);
    }

    /**
     * Undocumented function.
     *
     * @param Collection $collection
     * @param mixed      $value
     *
     * @return bool
     */
    protected function updateUserAllocatedFreeWeek($value)
    {
        $key = sprintf('%s-allocated', $this->generateTagKey());

        $this->cache->put($key, $value);

        return true;
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    protected function getCashOutAttempts()
    {
        $key = sprintf('%s-cashout-attempts', $this->generateTagKey());

        return $this->cache->get($key);
    }

    /**
     * Undocumented function.
     *
     * @return bool
     */
    protected function incrementCashOutAttempt()
    {
        $key = sprintf(
            '%s-cashout-attempts',
            $this->generateTagKey($this->collection)
        );

        if ($this->cache->has($key)) {
            $this->cache->put(
                $key,
                Math::add($this->cache->get($key), 1, 0)
            );
        } else {
            $this->cache->put($key, 1);
        }

        return true;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function generateTagKey()
    {
        list($year, $month, $week) = $this->getYearAndWeek();

        // this is where we determine if the month is december
        // yet the week number is 1, meanning that week
        // is already the last week combining the next year first week.
        if ($month === 12 && $week === 1) {
            ++$year;
        }

        $this->collection->setValue('interpreted_year', $year);
        $this->collection->setValue('interpreted_week', $week);

        return strtr('{year}-{week}-{user}', [
            '{year}' => $year,
            '{week}' => $week,
            '{user}' => $this->collection->userId(),
        ]);
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    protected function getYearAndWeek()
    {
        $date = new DateTime($this->collection->date());

        return [
            (int) $date->format('Y'),
            (int) $date->format('m'),
            (int) $date->format('W'),
        ];
    }

    /**
     * Undocumented function.
     *
     * @return bool
     */
    protected function stillInMinimumCashOut()
    {
        if ($this->getCashOutAttempts() <= static::NATURAL_FREE_MAX_CASHOUT) {
            return true;
        }

        return false;
    }

    /**
     * Determine if still free commission fee.
     *
     * @param string|float $basis
     *
     * @return bool
     */
    protected function isCashOutFreeWeek($basis)
    {
        // instead of using literal equal, we could use range
        // to hack the equally equal even having decimal places
        // 300 == 300.0000000
        if (
            $basis >= static::NATURAL_FREE_PER_WEEK &&
            $basis <= static::NATURAL_FREE_PER_WEEK
        ) {
            return true;
        }

        // if basis is still lower than the natural
        // free per week
        if ($basis < static::NATURAL_FREE_PER_WEEK) {
            return true;
        }

        return false;
    }
}
