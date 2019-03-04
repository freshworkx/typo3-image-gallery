<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Controller;

use Bitmotion\BmImageGallery\Domain\Model\Dto\CollectionInfo;
use Bitmotion\BmImageGallery\Factory\FileFactory;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Frontend\Resource\FileCollector;

class ListController extends ActionController
{
    protected $fileCollectionRepository;

    public function __construct(FileCollectionRepository $fileCollectionRepository)
    {
        $this->fileCollectionRepository = $fileCollectionRepository;

        parent::__construct();
    }

    public function initializeListAction()
    {
        if ($this->request->hasArgument('show')) {
            $this->settings['collection'] = $this->request->getArgument('show');
            $this->settings['showBackLink'] = true;
        }
    }

    /**
     * @throws NoSuchArgumentException
     * @throws StopActionException
     */
    public function defaultAction()
    {
        $collectionUids = $this->getCollectionsToDisplay($this->settings['collections']);
        $collectionUidCount = count($collectionUids);

        if ($collectionUidCount > 0) {
            $showOverview = count($collectionUids) > 1 ? true : false;

            if (array_key_exists('show', $this->request->getArguments())) {
                if ($this->request->getArgument('show') != '') {
                    if (in_array($this->request->getArgument('show'), $collectionUids)) {
                        $this->forward('list');
                    }
                }
            }

            if ($showOverview) {
                $this->forward('overview');
            } else {
                $this->forward('list');
            }
        }
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
                /** @var Logger $logger */
                $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger();
                $logger->warning('The file-collection with uid  "' . $collectionUid . '" could not be found or contents could not be loaded and won\'t be included in frontend output');
            }
        }

        return $fileCollections;
    }

    public function overviewAction()
    {
        $collectionInfoObjects = [];

        $collectionUids = $this->getCollectionsToDisplay($this->settings['collections']);

        foreach ($collectionUids as $collectionUid) {
            try {
                $fileCollection = $this->fileCollectionRepository->findByUid($collectionUid);
                if ($fileCollection instanceof AbstractFileCollection) {
                    $fileCollection->loadContents();
                    $fileObjects = $fileCollection->getItems();

                    $collectionInfo = new CollectionInfo($fileCollection, $fileObjects);

                    $collectionInfoObjects[] = $collectionInfo;
                }
            } catch (Exception $e) {
                /** @var Logger $logger */
                $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger();
                $logger->warning('The file-collection with uid  "' . $collectionUid . '" could not be found or contents could not be loaded and won\'t be included in frontend output');
            }
        }

        $this->view->assign('items', $collectionInfoObjects);
    }

    /**
     * @throws Exception\ResourceDoesNotExistException
     */
    public function listAction()
    {
        $fileCollections = $this->getCollectionsToDisplay($this->settings['collection']);
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
        $fileCollector->addFilesFromFileCollections($fileCollections);

        if ($this->settings['orderBy'] === '' || $this->settings['orderBy'] !== 'default') {
            $fileCollector->sort($this->settings['orderBy'], ($this->settings['sortingOrder'] ? $this->settings['sortingOrder'] : 'ascending'));
        }

        $fileFactory = GeneralUtility::makeInstance(FileFactory::class);
        $fileObjects = $fileFactory->getFileObjects($fileCollector->getFiles(), (int)$this->settings['maxItems']);

        $fileCollectionUid = array_shift($fileCollections);
        $fileCollection = $this->fileCollectionRepository->findByUid($fileCollectionUid);

        $this->view->assignMultiple([
            'fileCollection' => new CollectionInfo($fileCollection, $fileObjects),
            'items' => $fileObjects,
        ]);
    }
}
