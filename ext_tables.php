<?php
defined('TYPO3_MODE') || die('Access denied.');



$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ext-bm-image-gallery-wizard-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    [
        'source' => 'EXT:bm_image_gallery/Resources/Public/Icons/Bitmotion-Whirl.svg',
    ]
);
