<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Alter DB to UTF-8',
    'description' => 'With this extension you can set all tables and columns to UTF-8 collation (no converting)',
    'category' => 'be',
    'version' => '2.1.0',
    'state' => 'stable',
    'author' => 'Stefan Froemken',
    'author_email' => 'froemken@gmail.com',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.2.99',
            'typo3' => '12.4.0-12.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
