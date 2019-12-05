<?php

use Daison\Paysera\Manager;
use Daison\Paysera\Parsers\Csv;
use Daison\Paysera\Services\Commission;
use Daison\Paysera\Services\CurrencyExchange;

require __DIR__ . '/vendor/autoload.php';

$exchange = new CurrencyExchange();

$commission = new Commission();
$commission->setCurrencyExchange($exchange);

$parser = new Csv();
$parser->setPath(__DIR__ . '/tests/input.csv')->parse();

$collections = Manager::make()
    ->setCommission($commission)
    ->setParser($parser)
    ->handle();

$table = new LucidFrame\Console\ConsoleTable();
$table
    ->addHeader('Date')
    ->addHeader('User Id')
    ->addHeader('User Type')
    ->addHeader('Operation Type')
    ->addHeader('Amount')
    ->addHeader('Currency')
    ->addHeader('Raw Fee')
    ->addHeader('Converted Fee in (EUR)');

foreach ($collections as $collection) {
    $table->addRow()
        ->addColumn($collection->date())
        ->addColumn($collection->userId())
        ->addColumn($collection->userType())
        ->addColumn($collection->operationType())
        ->addColumn($collection->amount())
        ->addColumn($collection->currency())
        ->addColumn($collection->getValue('rawFee'))
        ->addColumn($collection->getValue('convertedFee'));
}

$table->display();
