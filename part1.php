<?php

use Daison\Paysera\Application;
use Daison\Paysera\Parsers\Csv;
use Daison\Paysera\Services\Commission;
use Daison\Paysera\Services\CurrencyExchange;
use Daison\Paysera\Services\Operators\CashIn;
use Daison\Paysera\Services\Operators\CashOutOld;

require __DIR__ . '/bootstrap.php';

$commission = new Commission;
$commission->setOperators([
    'cash_in'  => CashIn::class,
    'cash_out' => CashOutOld::class,
]);
$commission->setCurrencyExchange(new CurrencyExchange());

$collections = Application::make() // or: Manager::getInstance()
    ->setCommission($commission)
    ->setData(new Csv(__DIR__ . '/tests/input1.csv'))
    ->handle();

require __DIR__ . '/printer.php';
