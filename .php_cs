<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'ordered_imports' => true,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'php_unit_construct' => true,
        'phpdoc_align' => false,
        'class_definition' => [
            'multiLineExtendsEachSingleLine' => true,
        ]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('Resources')
            ->exclude('vendor')
            ->in(__DIR__)
    );
