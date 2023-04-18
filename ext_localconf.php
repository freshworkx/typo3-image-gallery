<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            $extensionKey,
            'GalleryList',
            [
                \Leuchtfeuer\BmImageGallery\Controller\ListController::class => 'list,gallery'
            ], []
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            $extensionKey,
            'GalleryDetail',
            [
                \Leuchtfeuer\BmImageGallery\Controller\ListController::class => 'gallery'
            ], []
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            $extensionKey,
            'SelectedGallery',
            [
                \Leuchtfeuer\BmImageGallery\Controller\ListController::class => 'selectedGallery'
            ], []
        );

        // Upgrade Wizards
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][\Leuchtfeuer\BmImageGallery\Updates\PluginUpdateWizard::class]
            = \Leuchtfeuer\BmImageGallery\Updates\PluginUpdateWizard::class;
    }, 'bm_image_gallery'
);
