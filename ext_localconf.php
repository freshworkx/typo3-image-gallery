<?php

use Freshworkx\BmImageGallery\Controller\ListController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        ExtensionUtility::configurePlugin(
            $extensionKey,
            'GalleryList',
            [
                ListController::class => 'list,gallery'
            ],
            [],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            $extensionKey,
            'GalleryDetail',
            [
                ListController::class => 'detail'
            ],
            [],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            $extensionKey,
            'SelectedGallery',
            [
                ListController::class => 'gallery'
            ],
            [],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

    }, 'bm_image_gallery'
);
