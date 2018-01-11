<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Simple Image Gallery',
    'description' => 'Simple gallery using file collections.',
    'category' => 'plugin',
    'author' => 'Florian Wessels, René Fritz, ',
    'author_email' => 'typo3-ext@bitmotion.de',
    'author_company' => 'Bitmotion GmbH',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => false,
    'version' => '2.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.7.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'clearcacheonload' => false,
];