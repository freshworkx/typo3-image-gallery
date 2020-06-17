<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        // Add content element wizard to PageTSConfig
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bm_image_gallery/Configuration/TSconfig/Page/ContentElementWizard/setup.tsconfig">'
        );

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'ext-bm-image-gallery-wizard-icon',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => 'EXT:bm_image_gallery/Resources/Public/Icons/Extension.svg',
            ]
        );
    }
);
