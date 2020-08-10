<?php
declare(strict_types = 1);
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extensionKey,
            'GalleryList',
            'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:ffds.display_mode.1'
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extensionKey,
            'GalleryDetail',
            'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:ffds.display_mode.2'
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extensionKey,
            'SelectedGallery',
            'LLL:EXT:bm_image_gallery/Resources/Private/Language/locallang_be.xlf:ffds.display_mode.3'
        );

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['bmimagegallery_gallerylist'] = 'recursive,select_key,pages';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['bmimagegallery_gallerylist'] = 'pi_flexform';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            'bmimagegallery_gallerylist',
            'FILE:EXT:bm_image_gallery/Configuration/FlexForms/GalleryList.xml'
        );

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['bmimagegallery_gallerydetail'] = 'recursive,select_key,pages';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['bmimagegallery_gallerydetail'] = 'pi_flexform';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            'bmimagegallery_gallerydetail',
            'FILE:EXT:bm_image_gallery/Configuration/FlexForms/GalleryDetail.xml'
        );

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['bmimagegallery_selectedgallery'] = 'recursive,select_key,pages';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['bmimagegallery_selectedgallery'] = 'pi_flexform';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            'bmimagegallery_selectedgallery',
            'FILE:EXT:bm_image_gallery/Configuration/FlexForms/SelectedGallery.xml'
        );
    }, 'bm_image_gallery'
);

