<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Simple Image Gallery',
    'description' => 'Simple gallery using FileCollections.',
    'category' => 'plugin',
    'author' => 'Florian Wessels, René Fritz, ',
    'author_email' => 'typo3-ext@bitmotion.de',
    'author_company' => 'Bitmotion GmbH',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => false,
    'version' => '2.0.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.99-8.7.99',
            'php' => '5.5',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
