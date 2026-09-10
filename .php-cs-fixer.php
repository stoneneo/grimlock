<?php

$finder = new PhpCsFixer\Finder()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

return new PhpCsFixer\Config()
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.phpunit.cache/.php-cs-fixer.cache');
