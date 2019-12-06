<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Domain\Repository;

/***
 *
 * This file is part of the "Simple Image Gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Florian Wessels <f.wessels@bitmotion.de>, Bitmotion GmbH
 *
 ***/

use Bitmotion\BmImageGallery\Domain\Transfer\CollectionInfo;
use Bitmotion\BmImageGallery\Factory\FileFactory;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Resource\FileCollector;

class FileCollectionRepository extends \TYPO3\CMS\Core\Resource\FileCollectionRepository
{
    public function getFileCollectionsToDisplay(string $collections): array
    {
        $collectionUids = GeneralUtility::trimExplode(',', $collections, true);
        $fileCollections = [];

        foreach ($collectionUids as $collectionUid) {
            try {
                $fileCollection = $this->findByUid((int)$collectionUid);
                if ($fileCollection instanceof AbstractFileCollection) {
                    $fileCollections[] = $collectionUid;
                }
            } catch (\Exception $e) {
                $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger();
                $logger->warning('The file-collection with uid  "' . $collectionUid . '" could not be found or contents could not be loaded and won\'t be included in frontend output');
            }
        }

        return $fileCollections;
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    public function getFileCollectionById(string $identifier, $orderBy = '', $maxItems = 0): array
    {
        $fileCollections = $this->getFileCollectionsToDisplay($identifier);
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
        $fileCollector->addFilesFromFileCollections($fileCollections);

        if ($orderBy === '' || $orderBy !== 'default') {
            $fileCollector->sort($orderBy, ($this->settings['sortingOrder'] ?? 'ascending'));
        }

        $collectionInfo = null;
        $fileFactory = GeneralUtility::makeInstance(FileFactory::class);
        $fileObjects = $fileFactory->getFileObjects($fileCollector->getFiles(), $maxItems);

        if (count($fileObjects) > 0) {
            $fileCollectionUid = array_shift($fileCollections);
            $fileCollection = $this->findByUid($fileCollectionUid);
            $collectionInfo = new CollectionInfo($fileCollection, $fileObjects);
        }

        return [
            'fileCollection' => $collectionInfo,
            'items' => $fileObjects,
        ];
    }
}
