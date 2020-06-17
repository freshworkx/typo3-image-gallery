<?php

declare(strict_types=1);

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <f.wessels@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

namespace Leuchtfeuer\BmImageGallery\Domain\Repository;

use Leuchtfeuer\BmImageGallery\Domain\Transfer\CollectionInfo;
use Leuchtfeuer\BmImageGallery\Factory\FileFactory;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileCollectionRepository as Typo3FileCollectionRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Resource\FileCollector;

class FileCollectionRepository extends Typo3FileCollectionRepository
{
    protected $languageUid;

    protected $languageField;

    protected $languagePointer;

    public function __construct(Context $context)
    {
        $this->languageUid = $context->getPropertyFromAspect('language', 'id');
        $this->languageField = $GLOBALS['TCA']['sys_file_collection']['ctrl']['languageField'];
        $this->languagePointer = $GLOBALS['TCA']['sys_file_collection']['ctrl']['transOrigPointerField'];
    }

    /**
     * @param string $collections Comma separated list of file collection identifier
     * @param bool   $asObject    Return sys_file_collection Objects (true) or an array of identifier (false)
     *
     * @return int[]|AbstractFileCollection[]
     */
    public function getFileCollectionsToDisplay(string $collections, bool $asObject = false): array
    {
        $collectionUids = GeneralUtility::intExplode(',', $collections, true);
        $fileCollections = [];

        foreach ($collectionUids as $collectionUid) {
            try {
                $this->getLocalizedFileCollection($collectionUid);
                $fileCollection = $this->findByUid($collectionUid);

                if (isset($fileCollections[$collectionUid])) {
                    continue;
                }

                if ($fileCollection instanceof AbstractFileCollection) {
                    $fileCollections[$collectionUid] = $fileCollection;
                }
            } catch (\Exception $e) {
                $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger();
                $logger->warning(sprintf(
                    'The file-collection with uid  "%s" could not be found or contents could not be loaded and won\'t be included in frontend output',
                    $collectionUid
                ));
            }
        }

        return ($asObject === true) ? $fileCollections : array_keys($fileCollections);
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    public function getFileCollectionById(string $identifier, string $orderBy = '', int $maxItems = 0, ?string $sortingOrder = null): array
    {
        $fileCollections = $this->getFileCollectionsToDisplay($identifier);
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
        $fileCollector->addFilesFromFileCollections($fileCollections);

        if ($orderBy === '' || $orderBy !== 'default') {
            $fileCollector->sort($orderBy, $sortingOrder ?? 'ascending');
        }

        $fileObjects = GeneralUtility::makeInstance(FileFactory::class)->getFileObjects($fileCollector->getFiles(), $maxItems);

        if (count($fileObjects) > 0) {
            $fileCollectionUid = array_shift($fileCollections);
            $fileCollection = $this->findByUid($fileCollectionUid);
            $collectionInfo = new CollectionInfo($fileCollection, $fileObjects);
        }

        return [
            'fileCollection' => $collectionInfo ?? null,
            'items' => $fileObjects,
        ];
    }

    protected function getLocalizedFileCollection(int &$fileCollectionUid)
    {
        $fileCollection = BackendUtility::getRecord('sys_file_collection', $fileCollectionUid);

        if ($this->languageUid !== (int)$fileCollection[$this->languageField]) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_collection');

            $localizedFileCollection = $queryBuilder
                ->select('uid')
                ->from('sys_file_collection')
                ->where($queryBuilder->expr()->eq($this->languageField, $queryBuilder->createNamedParameter($this->languageUid, \PDO::PARAM_INT)))
                ->andWhere($queryBuilder->expr()->eq($this->languagePointer, $queryBuilder->createNamedParameter($fileCollectionUid, \PDO::PARAM_INT)))
                ->execute()
                ->fetchColumn();

            if ($localizedFileCollection) {
                $fileCollectionUid = (int)$localizedFileCollection;
            }
        }
    }
}
