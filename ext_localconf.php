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

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            sprintf(
                '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:%s/Configuration/TSconfig/ContentElementWizard.tsconfig">',
                $extensionKey
            )
        );
    }, 'bm_image_gallery'
);


