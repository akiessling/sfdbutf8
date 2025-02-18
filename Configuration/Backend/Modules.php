<?php

declare(strict_types=1);

return [
    'sfdbutf8' => [
        'extensionName' => 'sfdbutf8',
        'parent' => 'tools',
        'access' => 'admin',
        'iconIdentifier' => 'sfdbutf8-backend-module',
        'labels' => 'LLL:EXT:sfdbutf8/Resources/Private/Language/locallang_mod.xlf',
        'controllerActions' => [
            \StefanFroemken\Sfdbutf8\Controller\Utf8Controller::class => 'show, dbCheck, convert'
        ],
        'navigationComponent' => '',
    ]
];
