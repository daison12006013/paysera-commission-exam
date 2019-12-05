<?php

declare(strict_types=1);

namespace Daison\Paysera\Services\Operators;

use Daison\Paysera\Services\CacheGlobals as Cache;
use Daison\Paysera\Services\Math;
use Daison\Paysera\Traits\ExchangeSetterTrait;
use Daison\Paysera\Transformers\Collection;
use DateTime;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
class CashOut
{
    use ExchangeSetterTrait;

    const COMMISSION_FEE        = '0.3';
    const MAX_FEE               = '5.00';
    const LEGAL_MINIMUM         = '0.5';
    const NATURAL_FREE_PER_WEEK = '1000';

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

        return Math::add(0, $this->{'feeFor'.ucfirst($type)}(), 2);
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForNatural()
    {
        $amount = $this->analyzeNaturalAmount($this->collection);

        return Math::mul(
            $amount,
            Math::div(static::COMMISSION_FEE, '100')
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
            Math::div(static::COMMISSION_FEE, 100)
        );
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    public function analyzeNaturalAmount(Collection $collection)
    {
        list($year, $week) = static::getYearAndWeek($collection->date());

        $key = strtr('{year}-{week}-{user}', [
            '{year}' => $year,
            '{week}' => $week,
            '{user}' => $collection->userId(),
        ]);

        if (!$this->cache->has($key)) {
            $this->cache->put($key, 0);
        }

        $allocated = $this->cache->get($key);

        // we need to know the allocated + the collection's amount
        // we will call it as our base value for now...
        $basis = abs(Math::add($allocated, $collection->amount()));

        // this condition, where we return 0, meaning that
        // the purchases still under the free week quota
        // thus, we shall return 0 instead.
        if (
            $basis === static::NATURAL_FREE_PER_WEEK
            || $basis < static::NATURAL_FREE_PER_WEEK
        ) {
            $this->cache->put(
                $key,
                Math::add($this->cache->get($key), $collection->amount())
            );

            return '0.00';
        }

        // this is where we determine if our basis is greater than
        // the quota, thus, we need to pre-calculate the value that
        // we need to deduct from the remaining quota it has
        elseif ($basis > static::NATURAL_FREE_PER_WEEK) {
            $remaining = Math::sub(static::NATURAL_FREE_PER_WEEK, $allocated);

            $this->cache->put(
                $key,
                Math::add($this->cache->get($key), $remaining)
            );

            return abs(Math::sub($collection->amount(), $remaining));
        }

        return Math::add(0, $collection->amount());
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    public static function getYearAndWeek(string $date)
    {
        $date = new DateTime($date);

        return [
            (int) $date->format('Y'),
            (int) $date->format('W'),
        ];
    }
}
