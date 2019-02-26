<?php
declare(strict_types=1);
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'bm_image_gallery',
    'List',
    'Gallery'
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ext-bm-image-gallery-wizard-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    [
        'source' => 'EXT:bm_image_gallery/Resources/Public/Icons/Bitmotion-Whirl.svg',
    ]
);
