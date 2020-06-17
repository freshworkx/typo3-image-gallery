<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'BmImageGallery',
            'List',
            [
                \Bitmotion\BmImageGallery\Controller\ListController::class => 'list,gallery,selectedGallery'
            ], []
        );
    }
);
