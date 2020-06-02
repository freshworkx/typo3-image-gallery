<?php
declare(strict_types = 1);
defined('TYPO3_MODE') || die('Access denied.');

//#########
// PLUGIN #
//#########
// TODO: Remove this when dropping TYPO3 9 LTS support.
if (version_compare(TYPO3_version, '10.0.0', '>=')) {
    $extensionKey = 'BmImageGallery';
} else {
    $extensionKey = 'Bitmotion.BmImageGallery';
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extensionKey,
    'List',
    'Gallery'
);

//###########
// FLEXFORM #
//###########
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['bmimagegallery_list'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['bmimagegallery_list'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'bmimagegallery_list',
    'FILE:EXT:bm_image_gallery/Configuration/FlexForms/ControllerActions.xml'
);
