<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Bitmotion.bm_image_gallery',
    'List',
    [
        'List' => 'list,gallery,selectedGallery',
    ],
    []
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bm_image_gallery/Configuration/TSconfig/ContentElementWizard.tsconfig">'
);
