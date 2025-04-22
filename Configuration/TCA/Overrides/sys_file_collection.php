<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

$galleryColumns = [
    'bm_image_gallery_path_segment' => [
        'exclude' => true,
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_path_segment',
        'config' => [
            'type' => 'slug',
            'generatorOptions' => [
                'fields' => ['title'],
                'prefixParentPageSlug' => false,
                'replacements' => [
                    '/' => '-',
                ],
            ],
            'fallbackCharacter' => '-',
            'prependSlash' => false,
            'eval' => 'uniqueInPid',
        ],
    ],
    'bm_image_gallery_location' => [
        'exclude' => true,
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_location',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim',
        ],
    ],
    'bm_image_gallery_date' => [
        'exclude' => true,
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_date',
        'config' => [
            'default' => 0,
            'type' => 'datetime',
            'format' => 'datetime',
        ],
    ],
    'bm_image_gallery_description' => [
        'exclude' => true,
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_description',
        'config' => [
            'type' => 'text',
            'enableRichtext' => true,
        ],
    ],
    'bm_image_gallery_preview_image' => [
        'exclude' => true,
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_preview_image',
        'config' => [
            'type' => 'file',
            'maxitems' => 1,
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns(
    'sys_file_collection',
    $galleryColumns
);

ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_collection',
    '--div--;LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:plugin.tab,' .
    implode(',', array_keys($galleryColumns)) . ','
);
