<?php

declare(strict_types=1);

include __DIR__ . '/vendor/autoload.php';

$finder = PhpCsFixer\Finder::create()
    ->in(['src'])
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'no_alternative_syntax' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
        'yoda_style' => false,
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=' => 'align',
                '=>' => 'align',
            ],
        ],
        'trailing_comma_in_multiline_array' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
;
