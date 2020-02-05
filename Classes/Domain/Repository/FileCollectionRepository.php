<?php
declare(strict_types = 1);
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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Resource\FileCollector;

class FileCollectionRepository extends \TYPO3\CMS\Core\Resource\FileCollectionRepository
{
    protected $languageUid;

    protected $languageField;

    protected $languagePointer;

    public function __construct()
    {
        $this->languageUid = $this->retrieveLanguageUid();
        $this->languageField = $GLOBALS['TCA']['sys_file_collection']['ctrl']['languageField'];
        $this->languagePointer = $GLOBALS['TCA']['sys_file_collection']['ctrl']['transOrigPointerField'];
    }

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
                $logger->warning('The file-collection with uid  "' . $collectionUid . '" could not be found or contents could not be loaded and won\'t be included in frontend output');
            }
        }

        return ($asObject === true) ? $fileCollections : array_keys($fileCollections);
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

    protected function retrieveLanguageUid(): int
    {
        if (version_compare(TYPO3_version, '9.0.0', '<')) {
            return (int)$GLOBALS['TSFE']->sys_language_uid;
        }

        $context = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Context\\Context');

        return $context->getAspect('language')->getId();
    }

    protected function getLocalizedFileCollection(&$fileCollectionUid)
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
                $fileCollectionUid = $localizedFileCollection;
            }
        }
    }
}
