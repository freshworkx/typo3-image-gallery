<?php
$EM_CONF['bm_image_gallery'] = [
    'title' => 'Simple Image Gallery',
    'description' => 'Simple gallery using file collections.',
    'version' => '4.1.0-dev',
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
            'typo3' => '8.7.0-10.2.99',
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
