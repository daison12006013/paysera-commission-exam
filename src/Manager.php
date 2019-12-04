<?php

declare (strict_types = 1);

namespace Daison\Paysera;

use Daison\Paysera\Contracts\ShouldComputeCommissions;

class Manager
{
    protected $exchange;
    protected $commission;
    protected $parsed;

    public static function make()
    {
        return new static();
    }

    public function setCommission(ShouldComputeCommissions $commission)
    {
        $this->commission = $commission;

        return $this;
    }

    public function setParser(CommonData $parsed)
    {
        $this->parsed = $parsed;

        return $this;
    }

    public function handle()
    {
        $recollections = [];

        foreach ($this->parsed->collections() as $collection) {
            $fee = $this->commission->compute($collection);

            $recollections[] = $collection;
        }

        return $recollections;
    }
}
