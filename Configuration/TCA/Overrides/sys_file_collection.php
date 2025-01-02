<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        $temporaryColumns = [
            'bm_image_gallery_path_segment' => [
                'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_path_segment', // phpcs:ignore
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
                'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_location', // phpcs:ignore
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim',
                ],
            ],
            'bm_image_gallery_date' => [
                'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_date', // phpcs:ignore
                'config' => [
                    'default' => 0,
                    'type' => 'datetime',
                    'format' => 'datetime',
                ],
            ],
            'bm_image_gallery_description' => [
                'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_description', // phpcs:ignore
                'config' => [
                    'type' => 'text',
                    'enableRichtext' => true,
                ],
            ],
            'bm_image_gallery_preview_image' => [
                'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_preview_image',
                'config' => [
                    'type' => 'file',
                    'maxitems' => 1,
                ],
            ],
        ];

        ExtensionManagementUtility::addTCAcolumns('sys_file_collection', $temporaryColumns);

        ExtensionManagementUtility::addToAllTCAtypes(
            'sys_file_collection',
            '--div--;LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_tab,' . implode(',', array_keys($temporaryColumns)) . ',' // phpcs:ignore
        );
    },
    'bm_image_gallery'
);
