<?php
declare(strict_types = 1);
defined('TYPO3_MODE') || die('Access denied.');

$temporaryColumns = [
    'bm_image_gallery_description' => [
        'label' => 'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_description',
        'config' => [
            'type' => 'text',
            'enableRichtext' => true,
        ],
    ],
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
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_collection', $temporaryColumns);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_collection',
    implode(',', array_keys($temporaryColumns)),
    '',
    'after:title'
);
