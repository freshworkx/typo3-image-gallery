<?php
$EM_CONF['bm_image_gallery'] = [
    'title' => 'Simple Image Gallery',
    'description' => 'Simple gallery using file collections.',
    'version' => '6.2.0',
    'category' => 'plugin',
    'author' => 'Jens Neumann',
    'author_email' => 'info@freshworkx.de',
    'state' => 'stable',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.11-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Freshworkx\\BmImageGallery\\' => 'Classes',
        ],
    ],
];
