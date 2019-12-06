<?php

require __DIR__ . '/bootstrap.php';

// ------------------------------------------------------------

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

echo sprintf("> Speed: %s\n", microtime(true) - DAISON_PAYSERA_START);
