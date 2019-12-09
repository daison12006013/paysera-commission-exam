<?php

define('DAISON_PAYSERA_START', microtime(true));

use Daison\Paysera\Application;
use Daison\Paysera\Parsers\Csv;

require __DIR__ . '/vendor/autoload.php';

$collections = Application::make() // or: Application::getInstance()
    ->setData(new Csv(__DIR__ . '/tests/input.csv'))
    ->handle();
