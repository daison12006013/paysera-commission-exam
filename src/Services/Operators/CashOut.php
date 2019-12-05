<?php

declare(strict_types=1);

namespace Daison\Paysera\Services\Operators;

use Daison\Paysera\Services\CacheGlobals as Cache;
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

        return $this->{'feeFor'.ucfirst($type)}();
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForNatural()
    {
        $amount = $this->analyzeNaturalAmount($this->collection);

        return bcmul(
            (string) $amount,
            bcdiv(static::COMMISSION_FEE, '100', 30),
            2
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

        return bcmul(
            $this->collection->amount(),
            bcdiv(static::COMMISSION_FEE, '100', 30),
            2
        );
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    public function analyzeNaturalAmount(Collection $collection)
    {
        list($year, $week) = static::getYearAndWeek($collection);

        $key = strtr('{year}-{week}-{user}', [
            '{year}' => $year,
            '{week}' => $week,
            '{user}' => $collection->userId(),
        ]);

        if (!$this->cache->has($key)) {
            $this->cache->put($key, 0);
        }

        $allocated = $this->cache->get($key);

        $abs = abs(bcadd((string) $allocated, (string) $collection->amount(), 2));

        if (
            $abs === static::NATURAL_FREE_PER_WEEK
            || $abs < static::NATURAL_FREE_PER_WEEK
        ) {
            $this->cache->put(
                $key,
                bcadd((string) $this->cache->get($key), (string) $collection->amount(), 2)
            );

            return 0;
        } elseif ($abs > static::NATURAL_FREE_PER_WEEK) {
            $absolute = bcsub(static::NATURAL_FREE_PER_WEEK, (string) $allocated, 2);
            $this->cache->put(
                $key,
                bcadd((string) $this->cache->get($key), (string) $absolute, 2)
            );

            return abs(bcsub((string) $collection->amount(), (string) $absolute, 2));
        }

        return (string) $collection->amount();
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    public static function getYearAndWeek(Collection $collection)
    {
        $date = new DateTime($collection->date());

        return [
            (int) $date->format('Y'),
            (int) $date->format('W'),
        ];
    }
}
