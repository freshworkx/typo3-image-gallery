<?php
declare(strict_types=1);
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Bitmotion.bm_image_gallery',
    'List',
    [
        'List' => 'default, overview, list',
    ],
    []
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bm_image_gallery/Configuration/TSconfig/ContentElementWizard.tsconfig">'
);
