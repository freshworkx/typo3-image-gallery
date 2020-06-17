<?php
declare(strict_types = 1);
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'BmImageGallery',
    'List',
    'Gallery'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['bmimagegallery_list'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['bmimagegallery_list'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'bmimagegallery_list',
    'FILE:EXT:bm_image_gallery/Configuration/FlexForms/ControllerActions.xml'
);
