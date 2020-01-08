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
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
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
                    $fileCollections[] = $fileCollection->getUid();
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

    /**
     * @throws ResourceDoesNotExistException
     */
    public function findByUid($uid)
    {
        $overlay = $this->findCollectionOverlay($uid);
        if (is_array($overlay)) {
            return $this->createDomainObject($overlay);
        }
        return parent::findByUid($uid);
    }

    protected function findCollectionOverlay($uid)
    {
        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder->select('*')
            ->from($this->table)
            ->where($queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)))
            ->andWhere($queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($this->getCurrentLaguageUid(), \PDO::PARAM_INT)))
            ->execute()
            ->fetch();
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->table);

        if ($this->getEnvironmentMode() === 'FE') {
            $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
        } else {
            $queryBuilder->getRestrictions()
                ->removeAll()
                ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        }

        return $queryBuilder;
    }

    /**
     * @return int
     */
    protected function getCurrentLaguageUid()
    {
        if (version_compare(TYPO3_version, '9.0.0', '<')) {
            return (int)$GLOBALS['TSFE']->sys_language_uid;
        }

        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        return $languageAspect->getId();
    }
}
