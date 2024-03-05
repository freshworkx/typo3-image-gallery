<?php
declare(strict_types = 1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        ExtensionUtility::registerPlugin(
            $extensionKey,
            'GalleryList',
            'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:ffds.display_mode.1'
        );
        ExtensionUtility::registerPlugin(
            $extensionKey,
            'GalleryDetail',
            'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:ffds.display_mode.2'
        );
        ExtensionUtility::registerPlugin(
            $extensionKey,
            'SelectedGallery',
            'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:ffds.display_mode.3'
        );

        $flexforms = [
            'bmimagegallery_gallerylist' => 'GalleryList',
            'bmimagegallery_gallerydetail' => 'GalleryDetail',
            'bmimagegallery_selectedgallery' => 'SelectedGallery',
        ];

        foreach ($flexforms as $key => $value) {
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$key] = 'recursive,select_key,pages';
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$key] = 'pi_flexform';

            ExtensionManagementUtility::addPiFlexFormValue(
                $key,
                'FILE:EXT:bm_image_gallery/Configuration/FlexForms/' . $value . '.xml'
            );
        }

    }, 'bm_image_gallery'
);

