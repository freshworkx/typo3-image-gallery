<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        // TODO: Remove this condition when dropping TYPO3 v9 support.
        if (version_compare(TYPO3_version, '10.0.0', '>=')) {
            $extensionName = 'BmImageGallery';
            $controllerName = \Bitmotion\BmImageGallery\Controller\ListController::class;
        } else {
            $extensionName = 'Bitmotion.BmImageGallery';
            $controllerName = 'List';
        }

        // Configure Auth0 plugin
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            $extensionName,
            'List',
            [$controllerName => 'list,gallery,selectedGallery'],
            []
        );
    }, 'bm_image_gallery'
);


