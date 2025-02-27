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
        '--div--;Plugin,file_collections,pi_flexform,',
        $pluginSignature,
        'after:subheader',
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:bm_image_gallery/Configuration/FlexForms/PluginSettings.xml',
        $pluginSignature
    );
}
