<?php

use Freshworkx\BmImageGallery\Controller\ListController;
use Freshworkx\BmImageGallery\Resource\Collection\CategoryBasedFileCollection;
use Freshworkx\BmImageGallery\Resource\Collection\FolderBasedFileCollection;
use Freshworkx\BmImageGallery\Resource\Collection\StaticFileCollection;
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

        // extend [XClass] core collections
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['category'] = CategoryBasedFileCollection::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['folder'] = FolderBasedFileCollection::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['static'] = StaticFileCollection::class;

    }, 'bm_image_gallery'
);
