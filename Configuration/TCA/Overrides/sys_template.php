<?php
declare(strict_types = 1);
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'bm_image_gallery',
    'Configuration/TypoScript',
    'Gallery'
);
