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

use Bitmotion\BmImageGallery\Domain\Repository\FileCollectionRepository;
use Bitmotion\BmImageGallery\Domain\Transfer\CollectionInfo;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;

class ListController extends ActionController
{
    protected $fileCollectionRepository;

    public function __construct(FileCollectionRepository $fileCollectionRepository)
    {
        $this->fileCollectionRepository = $fileCollectionRepository;

        if (version_compare(TYPO3_version, '10.0.0', '<')) {
            parent::__construct();
        }
    }

    public function listAction()
    {
        $collectionInfoObjects = [];
        $collectionUids = $this->fileCollectionRepository->getFileCollectionsToDisplay($this->settings['collections']);

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
        $this->view->assignMultiple(
            $this->fileCollectionRepository->getFileCollectionById(
                $this->request->getArgument('show'),
                $this->settings['orderBy'],
                (int)$this->settings['maxItems']
            )
        );
    }

    /**
     * @throws Exception\ResourceDoesNotExistException
     */
    public function selectedGalleryAction()
    {
        $this->view->assignMultiple(
            $this->fileCollectionRepository->getFileCollectionById(
                (string)$this->settings['collection'],
                $this->settings['orderBy'],
                (int)$this->settings['maxItems']
            )
        );
    }

    /**
     * @deprecated Will be removed in next major version. Use the repository instead.
     */
    protected function getCollectionsToDisplay(string $collections): array
    {
        return $this->fileCollectionRepository->getFileCollectionsToDisplay($collections);
    }

    /**
     * @throws Exception\ResourceDoesNotExistException
     * @deprecated  Will be removed in next major version. Use the repository instead.
     */
    protected function getFileCollectionById(string $identifier): array
    {
        return $this->fileCollectionRepository->getFileCollectionById(
            $identifier,
            $this->settings['orderBy'],
            (int)$this->settings['maxItems']
        );
    }
}
