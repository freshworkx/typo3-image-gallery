<?php

declare(strict_types=1);

use Freshworkx\BmImageGallery\Controller\GalleryController;
use Freshworkx\BmImageGallery\Resource\Collection\CategoryBasedFileCollection;
use Freshworkx\BmImageGallery\Resource\Collection\FolderBasedFileCollection;
use Freshworkx\BmImageGallery\Resource\Collection\StaticFileCollection;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();


ExtensionUtility::configurePlugin(
    'BmImageGallery',
    'GalleryList',
    [
        GalleryController::class => 'list,gallery'
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'BmImageGallery',
    'GalleryDetail',
    [
        GalleryController::class => 'detail'
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'BmImageGallery',
    'SelectedGallery',
    [
        GalleryController::class => 'gallery'
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// extend [XClass] core collections
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['category'] = CategoryBasedFileCollection::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['folder'] = FolderBasedFileCollection::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['static'] = StaticFileCollection::class;
