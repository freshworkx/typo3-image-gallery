<?php
declare(strict_types = 1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
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
                    'type' => 'datetime',
                    'format' => 'datetime',
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

        ExtensionManagementUtility::addTCAcolumns('sys_file_collection', $temporaryColumns);

        ExtensionManagementUtility::addToAllTCAtypes(
            'sys_file_collection',
            '--div--;LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_tab,' . implode(',', array_keys($temporaryColumns)) . ','
        );
    }, 'bm_image_gallery'
);
