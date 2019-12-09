<?php

use Daison\Paysera\Application;
use Daison\Paysera\Parsers\Csv;

require __DIR__ . '/bootstrap.php';

$collections = Application::make() // or: Manager::getInstance()
    ->setData(new Csv(__DIR__ . '/tests/input2.csv'))
    ->handle();

require __DIR__ . '/printer.php';
