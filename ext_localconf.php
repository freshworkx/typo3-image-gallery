<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('Bitmotion.' . $_EXTKEY, 'List',
    [
        'List' => 'default, overview, list',
    ],
    []);