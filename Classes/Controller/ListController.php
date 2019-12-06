<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Controller;

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

use Bitmotion\BmImageGallery\Domain\Model\Dto\CollectionInfo;
use Bitmotion\BmImageGallery\Factory\FileFactory;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use TYPO3\CMS\Frontend\Resource\FileCollector;

class ListController extends ActionController
{
    protected $fileCollectionRepository;

    public function __construct(FileCollectionRepository $fileCollectionRepository)
    {
        $this->fileCollectionRepository = $fileCollectionRepository;

        parent::__construct();
    }

    protected function getCollectionsToDisplay(string $collections): array
    {
        $collectionUids = GeneralUtility::trimExplode(',', $collections, true);
        $fileCollections = [];

        foreach ($collectionUids as $collectionUid) {
            try {
                $fileCollection = $this->fileCollectionRepository->findByUid((int)$collectionUid);
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

    public function listAction()
    {
        $collectionInfoObjects = [];

        $collectionUids = $this->getCollectionsToDisplay($this->settings['collections']);

        foreach ($collectionUids as $collectionUid) {
            try {
                $fileCollection = $this->fileCollectionRepository->findByUid($collectionUid);
                if ($fileCollection instanceof AbstractFileCollection) {
                    $fileCollection->loadContents();
                    $fileObjects = $fileCollection->getItems();

                    // Add Collection only if it has items
                    if (count($fileObjects) > 0) {
                        $collectionInfo = new CollectionInfo($fileCollection, $fileObjects);
                        $collectionInfoObjects[] = $collectionInfo;
                    }
                }
            } catch (Exception $e) {
                $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger();
                $logger->warning('The file-collection with uid  "' . $collectionUid . '" could not be found or contents could not be loaded and won\'t be included in frontend output');
            }
        }

        $this->view->assign('items', $collectionInfoObjects);
    }

    /**
     * @throws Exception\ResourceDoesNotExistException
     * @throws NoSuchArgumentException
     */
    public function galleryAction()
    {
        $this->view->assignMultiple($this->getFileCollectionById($this->request->getArgument('show')));
    }

    /**
     * @throws Exception\ResourceDoesNotExistException
     */
    public function selectedGalleryAction()
    {
        $this->view->assignMultiple($this->getFileCollectionById((string)$this->settings['collection']));
    }

    /**
     * @throws Exception\ResourceDoesNotExistException
     */
    protected function getFileCollectionById(string $identifier): array
    {
        $fileCollections = $this->getCollectionsToDisplay($identifier);
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
        $fileCollector->addFilesFromFileCollections($fileCollections);

        if ($this->settings['orderBy'] === '' || $this->settings['orderBy'] !== 'default') {
            $fileCollector->sort($this->settings['orderBy'], ($this->settings['sortingOrder'] ?? 'ascending'));
        }

        $collectionInfo = null;
        $fileFactory = GeneralUtility::makeInstance(FileFactory::class);
        $fileObjects = $fileFactory->getFileObjects($fileCollector->getFiles(), (int)$this->settings['maxItems']);

        if (count($fileObjects) > 0) {
            $fileCollectionUid = array_shift($fileCollections);
            $fileCollection = $this->fileCollectionRepository->findByUid($fileCollectionUid);
            $collectionInfo = new CollectionInfo($fileCollection, $fileObjects);
        }

        return [
            'fileCollection' => $collectionInfo,
            'items' => $fileObjects,
        ];
    }
}
