<?php

namespace Daison\Paysera\Services\Operators;

use Daison\Paysera\Services\CacheGlobals as Cache;
use Daison\Paysera\Services\Traits\ExchangeSetterTrait;
use DateTime;

class CashOut
{
    use ExchangeSetterTrait;

    const COMMISSION_FEE        = 0.3;
    const MAX_FEE               = 5.00;
    const LEGAL_MINIMUM         = 0.5;
    const NATURAL_FREE_PER_WEEK = 1000;

    public function __construct($collection)
    {
        $this->collection = $collection;
        $this->cache      = Cache::make();
    }

    public function fee()
    {
        $type = $this->collection->userType();

        return $this->{'feeFor' . ucfirst($type)}();
    }

    protected function feeForNatural()
    {
        $amount = $this->getNaturalAmount($this->collection);

        return bcmul(
            $amount,
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

        return bcmul(
            $this->collection->amount(),
            bcdiv(static::COMMISSION_FEE, 100, 30),
            2
        );
    }

    public function getNaturalAmount($c)
    {
        list($year, $week) = static::getYearAndWeek($c);

        $key = strtr('{year}-{week}-{user}', [
            '{year}' => $year,
            '{week}' => $week,
            '{user}' => $c->userId(),
        ]);

        if (!$this->cache->has($key)) {
            $this->cache->put($key, 0);
        }

        $allocated = $this->cache->get($key);

        $abs = abs($allocated + $c->amount());

        if (
            $abs == static::NATURAL_FREE_PER_WEEK
            || $abs < static::NATURAL_FREE_PER_WEEK
        ) {
            $this->cache->put(
                $key,
                $this->cache->get($key) + $c->amount()
            );

            return 0;
        }

        elseif ($abs > static::NATURAL_FREE_PER_WEEK) {
            $absolute = static::NATURAL_FREE_PER_WEEK - $allocated;
            $this->cache->put(
                $key,
                $this->cache->get($key) + $absolute
            );

            return abs($c->amount() - $absolute);
        }

        return $c->amount();
    }

    public static function getYearAndWeek($collection)
    {
        $date = new DateTime($collection->date());

        return [
            (int) $date->format('Y'),
            (int) $date->format('W'),
        ];
    }
}
