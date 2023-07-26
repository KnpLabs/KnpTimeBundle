<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('Tests/app/var')
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
            'allow_unused_params' => false,
            'remove_inheritdoc' => true,
        ],
    ])
    ->setFinder($finder)
    ->setUsingCache(false)
;
