<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Domain\Model\Dto;

/***
 *
 * This file is part of the "Simple Image Gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Florian Wessels <f.wessels@bitmotion.de>, Bitmotion GmbH
 *
 ***/

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @deprecated Use transfer object instead.
 */
class CollectionInfo extends \Bitmotion\BmImageGallery\Domain\Transfer\CollectionInfo {}
