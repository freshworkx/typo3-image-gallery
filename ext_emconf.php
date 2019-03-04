<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Simple Image Gallery',
    'description' => 'Simple gallery using file collections.',
    'version' => '4.0.0-dev',
    'category' => 'plugin',
    'author' => 'Bitmotion GmbH',
    'author_email' => 'typo3-ext@bitmotion.de',
    'author_company' => 'Bitmotion GmbH',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => false,
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Bitmotion\\BmImageGallery\\' => 'Classes',
        ],
    ],
];
