<?php

declare(strict_types=1);

namespace Daison\Paysera;

use Daison\Paysera\Contracts\ShouldComputeCommissions;
use Daison\Paysera\Contracts\ShouldProvideCollection;
use Daison\Paysera\Services\Commission;
use Daison\Paysera\Services\CurrencyExchange;
use Daison\Paysera\Traits\MakeableTrait;

/**
 * This is the application or other developers calls it as a Manager
 * where it lives all the bootstrap processes.
 *
 * @author Daison Carino <daison12006013@gmail.com>
 */
class Application
{
    use MakeableTrait;

    /**
     * Undocumented variable.
     *
     * @var \Daison\Paysera\Contracts\ShouldComputeCommissions
     */
    protected $commission;

    /**
     * Undocumented variable.
     *
     * @var \Daison\Paysera\Contracts\ShouldProvideCollection
     */
    protected $collector;

    /**
     * Undocumented function.
     */
    public function __construct()
    {
        $exchange   = new CurrencyExchange();
        $commission = new Commission();
        $commission->setCurrencyExchange($exchange);

        $this->setCommission($commission);
    }

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setCommission(ShouldComputeCommissions $commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setData(ShouldProvideCollection $collector)
    {
        $this->collector = $collector;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    public function handle()
    {
        foreach ($this->collector->collections() as $collection) {
            $this->commission->compute($collection);
        }

        return $this->collector->collections();
    }
}
