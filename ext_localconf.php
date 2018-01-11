<?php
defined('TYPO3_MODE') || die('Access denied.');


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Bitmotion.bm_image_gallery',
    'List',
    [
        'List' => 'default, overview, list',
    ],
    []
);