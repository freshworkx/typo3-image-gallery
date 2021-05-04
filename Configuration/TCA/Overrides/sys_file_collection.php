<?php
declare(strict_types = 1);
defined('TYPO3_MODE') || die('Access denied.');

$temporaryColumns = [
    'bm_image_gallery_path_segment' => [
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
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_location',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim',
        ],
    ],
    'bm_image_gallery_date' => [
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_date',
        'config' => [
            'default' => 0,
            'type' => 'input',
            'renderType' => 'inputDateTime',
            'eval' => 'datetime,int',
        ],
    ],
    'bm_image_gallery_description' => [
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_description',
        'config' => [
            'type' => 'text',
            'enableRichtext' => true,
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_collection', $temporaryColumns);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_collection',
    '--div--;LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_tab,' . implode(',', array_keys($temporaryColumns))
);
