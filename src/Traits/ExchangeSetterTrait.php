<?php

declare(strict_types=1);

namespace Daison\Paysera\Traits;

use Daison\Paysera\Contracts\ShouldConvertCurrencies;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
trait ExchangeSetterTrait
{
    /**
     * Undocumented variable.
     *
     * @var \Daison\Paysera\Contracts\ShouldConvertCurrencies
     */
    protected $exchange;

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setCurrencyExchange(ShouldConvertCurrencies $exchange)
    {
        $this->exchange = $exchange;

        return $this;
    }
}
