<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

$plugins = ['GalleryList', 'GalleryDetail', 'SelectedGallery'];
foreach ($plugins as $pluginName) {
    $pluginSignature = ExtensionUtility::registerPlugin(
        'BmImageGallery',
        $pluginName,
        'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:plugin.' . $pluginName,
        'bm-image-gallery',
    );
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin,file_collections,pi_flexform',
        $pluginSignature,
        'after:palette:headers',
    );

    $GLOBALS['TCA']['tt_content']['types'][$pluginSignature]['columnsOverrides']['pi_flexform'] = [
        'config' => [
            'ds' => 'FILE:EXT:bm_image_gallery/Configuration/FlexForms/PluginSettings.xml'
        ],
    ];
}

$GLOBALS['TCA']['tt_content']['types']['bmimagegallery_gallerylist']['columnsOverrides']['file_collections'] = [
    'config' => [
        'minitems' => 1,
        'maxitems' => 999,
        'fieldControl' => [
            'addRecord' => [
                'disabled' => true
            ]
        ]
    ]
];

$GLOBALS['TCA']['tt_content']['types']['bmimagegallery_gallerydetail']['columnsOverrides']['file_collections'] = [
    'config' => [
        'type' => 'passthrough'
    ]
];

$GLOBALS['TCA']['tt_content']['types']['bmimagegallery_selectedgallery']['columnsOverrides']['file_collections'] = [
    'config' => [
        'minitems' => 1,
        'maxitems' => 1,
        'size' => 1,
        'fieldControl' => [
            'addRecord' => [
                'disabled' => true
            ]
        ]
    ]
];
