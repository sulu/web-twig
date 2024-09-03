<?php

$header = <<<'EOF'
This file is part of Sulu.

(c) Sulu GmbH

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor'])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'ordered_imports' => true,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'phpdoc_align' => false,
        'class_definition' => false,
        'linebreak_after_opening_tag' => true,
        'declare_strict_types' => true,
        'mb_str_functions' => false,
        'no_php4_constructor' => true,
        'no_superfluous_phpdoc_tags' => false,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'php_unit_strict' => true,
        'phpdoc_order' => true,
        'single_line_throw' => false,
        'strict_comparison' => true,
        'strict_param' => true,
        'header_comment' => ['header' => $header],
        'native_constant_invocation' => true,
        'native_function_casing' => true,
        'native_function_invocation' => false,
        'get_class_to_class_keyword' => false, // should be enabled as soon as support for php < 8 is dropped
        'nullable_type_declaration_for_default_null_value' => true,
        'no_null_property_initialization' => false,
        'fully_qualified_strict_types' => false,
        'new_with_parentheses' => true,
        'modernize_strpos' => false,
    ])
    ->setFinder($finder);

return $config;
