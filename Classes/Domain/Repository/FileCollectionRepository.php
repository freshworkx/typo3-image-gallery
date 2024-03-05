<?php

declare(strict_types=1);

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Dev <dev@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

namespace Leuchtfeuer\BmImageGallery\Domain\Repository;

use Leuchtfeuer\BmImageGallery\Domain\Transfer\CollectionInfo;
use Leuchtfeuer\BmImageGallery\Factory\FileFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileCollectionRepository as Typo3FileCollectionRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Resource\FileCollector;

class FileCollectionRepository extends Typo3FileCollectionRepository implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const SORTING_PROPERTY_DEFAULT = 'default';

    const SORTING_ORDER_ASC = 'ascending';
    const SORTING_ORDER_DESC = 'descending';
    const SORTING_ORDER_RAND = 'random';

    const TABLE_NAME = 'sys_file_collection';

    protected $languageUid;

    protected $languageField;

    protected $languagePointer;

    public function __construct(Context $context)
    {
        $this->languageUid = $context->getPropertyFromAspect('language', 'id');
        $this->languageField = $GLOBALS['TCA'][self::TABLE_NAME]['ctrl']['languageField'];
        $this->languagePointer = $GLOBALS['TCA'][self::TABLE_NAME]['ctrl']['transOrigPointerField'];
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
                $this->logger->warning(
                    sprintf(
                        'The file-collection with uid  "%s" could not be found or contents could not be loaded and won\'t be included in frontend output',
                        $collectionUid
                    )
                );
            }
        }

        return ($asObject === true) ? $fileCollections : array_keys($fileCollections);
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    public function getFileCollectionById(
        string $identifier,
        string $sortingProperty = self::SORTING_PROPERTY_DEFAULT,
        int $maxItems = 0,
        string $sortingOrder = self::SORTING_ORDER_ASC
    ): array {
        $fileCollections = $this->getFileCollectionsToDisplay($identifier);
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
        $fileCollector->addFilesFromFileCollections($fileCollections);

        if ($sortingProperty !== self::SORTING_PROPERTY_DEFAULT) {
            $fileCollector->sort($sortingProperty, $sortingOrder);
        }

        $fileObjects = GeneralUtility::makeInstance(FileFactory::class)->getFileObjects($fileCollector->getFiles(), $maxItems);

        if (!empty($fileObjects)) {
            $collectionInfo = new CollectionInfo(
                $this->findByUid(array_shift($fileCollections)),
                $fileObjects
            );
        }

        return [
            'fileCollection' => $collectionInfo ?? null,
            'items' => $fileObjects,
        ];
    }

    protected function getLocalizedFileCollection(int &$fileCollectionUid)
    {
        $fileCollection = BackendUtility::getRecord(self::TABLE_NAME, $fileCollectionUid);

        if ($this->languageUid !== (int)$fileCollection[$this->languageField]) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE_NAME);

            $localizedFileCollection = $queryBuilder
                ->select('uid')
                ->from(self::TABLE_NAME)
                ->where($queryBuilder->expr()->eq($this->languageField, $queryBuilder->createNamedParameter($this->languageUid, \PDO::PARAM_INT)))
                ->andWhere($queryBuilder->expr()->eq($this->languagePointer, $queryBuilder->createNamedParameter($fileCollectionUid, \PDO::PARAM_INT)))
                ->executeQuery()
                ->fetchOne();

            if ($localizedFileCollection) {
                $fileCollectionUid = (int)$localizedFileCollection;
            }
        }
    }
}
