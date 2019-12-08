<?php
defined('TYPO3_MODE') || die('Access denied.');


call_user_func(
    function ($extensionKey) {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'ext-bm-image-gallery-wizard-icon',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => sprintf('EXT:%s/Resources/Public/Icons/Extension.svg', $extensionKey),
            ]
        );
    }, 'bm_image_gallery'
);
