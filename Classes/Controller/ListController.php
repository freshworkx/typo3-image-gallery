<?php

declare(strict_types=1);

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Jens Neumann <info@freshworkx.de>
 */

namespace Freshworkx\BmImageGallery\Controller;

use Freshworkx\BmImageGallery\Domain\Repository\FileCollectionRepository;
use Freshworkx\BmImageGallery\Domain\Transfer\CollectionInfo;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ListController extends ActionController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(protected FileCollectionRepository $fileCollectionRepository)
    {
    }

    public function listAction(): ResponseInterface
    {
        $collectionInfos = [];
        $fileCollections = $this->fileCollectionRepository->getFileCollectionsToDisplay(
            $this->settings['collections'] ?? '',
            true
        );

        /** @var AbstractFileCollection $fileCollection */
        foreach ($fileCollections as $fileCollection) {
            try {
                $fileCollection->loadContents();
                $fileObjects = $fileCollection->getItems();

                // Add collection only if it has items
                if (count($fileObjects) > 0) {
                    $collectionInfos[] = new CollectionInfo($fileCollection, $fileObjects);
                }
            } catch (\Exception) {
                $this->logger->warning(
                    sprintf(
                        'The file-collection with ID "%s" could not be found or contents could not be loaded and won\'t be included in frontend output', // phpcs:ignore
                        $fileCollection->getIdentifier()
                    )
                );
            }
        }

        $this->view->assign('items', $collectionInfos);
        return $this->htmlResponse();
    }

    /**
     * @throws Exception\ResourceDoesNotExistException
     */
    public function galleryAction(): ResponseInterface
    {
        $identifier = $this->settings['collection'] ?? $this->request->getQueryParams()['tx_bmimagegallery_gallerylist']['show'] ?? 0; // phpcs:ignore
        $this->view->assignMultiple($this->getCollection((string)$identifier));
        return $this->htmlResponse();
    }

    /**
     * @throws Exception\ResourceDoesNotExistException
     */
    public function detailAction(): ResponseInterface
    {
        $identifier = $this->request->getQueryParams()['tx_bmimagegallery_gallerydetail']['show'] ?? 0;
        $this->view->setTemplate('Gallery'); // @phpstan-ignore-line
        $this->view->assignMultiple($this->getCollection((string)$identifier));
        return $this->htmlResponse();
    }

    /**
     * @return array<string, array<File>|CollectionInfo|null>
     * @throws Exception\ResourceDoesNotExistException
     */
    protected function getCollection(string $identifier): array
    {
        return $this->fileCollectionRepository->getFileCollectionById(
            $identifier,
            $this->settings['orderBy'],
            (int)$this->settings['maxItems'],
            $this->settings['sortingOrder']
        );
    }
}
