<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        $plugins = [
            'GalleryList' => 'display_mode.1',
            'GalleryDetail' => 'display_mode.2',
            'SelectedGallery' => 'display_mode.3'
        ];
        foreach ($plugins as $pluginName => $label) {
            $pluginSignature = ExtensionUtility::registerPlugin(
                $extensionKey,
                $pluginName,
                'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:ffds.' . $label,
                'bm-image-gallery',
                'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:bm_image_gallery_tab'
            );
            ExtensionManagementUtility::addToAllTCAtypes(
                'tt_content',
                '--div--;Plugin,pi_flexform,',
                $pluginSignature,
                'after:subheader',
            );
            ExtensionManagementUtility::addPiFlexFormValue(
                '*',
                'FILE:EXT:bm_image_gallery/Configuration/FlexForms/' . $pluginName . '.xml',
                $pluginSignature
            );
        }
    },
    'bm_image_gallery'
);
