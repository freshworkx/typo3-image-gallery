<?php
declare(strict_types = 1);
defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $extensionKey,
            'Configuration/TypoScript',
            'Simple Image Gallery'
        );
    }, 'bm_image_gallery'
);
