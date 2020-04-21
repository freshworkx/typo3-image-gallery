<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Bitmotion.' . $extensionKey,
            'List',
            [
                'List' => 'list,gallery,selectedGallery',
            ],
            []
        );
    }, 'bm_image_gallery'
);


