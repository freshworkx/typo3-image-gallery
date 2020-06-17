<?php
declare(strict_types = 1);
namespace Leuchtfeuer\BmImageGallery\Controller;

/***
 *
 * This file is part of the "Simple Image Gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Florian Wessels <f.wessels@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 *
 ***/

use Leuchtfeuer\BmImageGallery\Domain\Repository\FileCollectionRepository;
use Leuchtfeuer\BmImageGallery\Domain\Transfer\CollectionInfo;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;

class ListController extends ActionController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $fileCollectionRepository;

    public function __construct(FileCollectionRepository $fileCollectionRepository)
    {
        $this->fileCollectionRepository = $fileCollectionRepository;
    }

    public function listAction()
    {
        $collectionInfos = [];
        $fileCollections = $this->fileCollectionRepository->getFileCollectionsToDisplay($this->settings['collections'], true);

        /** @var AbstractFileCollection $fileCollection */
        foreach ($fileCollections as $fileCollection) {
            try {
                $fileCollection->loadContents();
                $fileObjects = $fileCollection->getItems();

                // Add collection only if it has items
                if (count($fileObjects) > 0) {
                    $collectionInfos[] = new CollectionInfo($fileCollection, $fileObjects);
                }
            } catch (\Exception $e) {
                $this->logger->warning(sprintf(
                    'The file-collection with ID "%s" could not be found or contents could not be loaded and won\'t be included in frontend output',
                    $fileCollection->getIdentifier()
                ));
            }
        }

        $this->view->assign('items', $collectionInfos);
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
                (int)$this->settings['maxItems'],
                $this->settings['sortingOrder']
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
                (int)$this->settings['maxItems'],
                $this->settings['sortingOrder']
            )
        );
    }
}
