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

use Exception;
use Freshworkx\BmImageGallery\Resource\Collection\CategoryBasedFileCollection;
use Freshworkx\BmImageGallery\Resource\Collection\FolderBasedFileCollection;
use Freshworkx\BmImageGallery\Resource\Collection\StaticFileCollection;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Resource\FileCollector;

class GalleryController extends ActionController
{
    public function __construct(
        private readonly FileCollectionRepository $fileCollectionRepository,
        private readonly FileRepository $fileRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function listAction(): ResponseInterface
    {
        $collectionInfos = [];
        $currentContentObject = $this->request->getAttribute('currentContentObject');

        $collections = GeneralUtility::trimExplode(',', $currentContentObject->data['file_collections'], true);
        foreach ($collections as $collectionUid) {
            $collectionInfo = $this->getCollectionInfo((int)$collectionUid);
            if ($collectionInfo !== []) {
                $collectionInfos[] = $collectionInfo;
            }
        }

        $this->view->assign('collectionInfos', $collectionInfos);
        return $this->htmlResponse();
    }

    public function galleryAction(): ResponseInterface
    {
        $queryParams = $this->request->getQueryParams();
        $identifier = $this->settings['collection'] ?? $queryParams['tx_bmimagegallery_gallerylist']['show'] ?? 0;
        $this->view->assign('fileCollection', $this->getCollectionInfo((int)$identifier, true));
        return $this->htmlResponse();
    }

    public function detailAction(): ResponseInterface
    {
        $queryParams = $this->request->getQueryParams();
        $identifier = $queryParams['tx_bmimagegallery_gallerydetail']['show'] ?? 0;
        $this->view->setTemplate('Gallery'); // @phpstan-ignore-line
        $this->view->assign('fileCollection', $this->getCollectionInfo((int)$identifier, true));
        return $this->htmlResponse();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionInfo(int $identifier, bool $withItems = false): array
    {
        try {
            /** @var CategoryBasedFileCollection|FolderBasedFileCollection|StaticFileCollection|null $fileCollection */
            $fileCollection = $this->fileCollectionRepository->findByUid($identifier);

            // return if collection not found
            if ($fileCollection === null) {
                return [];
            }

            // load contents and get items
            $fileCollection->loadContents();
            $items = $fileCollection->getItems();

            // return if collection is empty
            if (count($items) === 0) {
                return [];
            }

            // gallery description with fallback to description of collection
            $description = ($fileCollection->getGalleryDescription() !== '') ? $fileCollection->getGalleryDescription() : $fileCollection->getDescription();

            // preview image with fallback to first image of collection (old behavior)
            $previewImage = $this->fileRepository->findByRelation(
                'sys_file_collection',
                'bm_image_gallery_preview_image',
                $fileCollection->getUid()
            );
            $previewImage = ($previewImage === []) ? reset($items) : reset($previewImage);

            // return infos
            return [
                'identifier' => $fileCollection->getUid(),
                'itemCount' => count($items),
                'title' => $fileCollection->getTitle(),
                'location' => $fileCollection->getGalleryLocation(),
                'description' => $description,
                'date' => $fileCollection->getGalleryDate(),
                'previewImage' => $previewImage,
                'items' => ($withItems) ? $this->sortAndLimitItems($fileCollection) : [],
            ];
        } catch (Exception $exception) {
            $this->logger->log(LogLevel::ERROR, $exception->getMessage());
            $this->logger->log(
                LogLevel::WARNING,
                sprintf(
                    'The file-collection with ID "%s" could not be loaded and won\'t be included',
                    $identifier
                )
            );

            return [];
        }
    }

    /**
     * @return array<int, FileInterface>
     */
    protected function sortAndLimitItems(AbstractFileCollection $fileCollection): array
    {
        /** @var FileCollector $fileCollector */
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
        $fileCollector->addFilesFromFileCollection($fileCollection->getUid());
        $fileCollector->sort($this->settings['orderBy'], $this->settings['sortingOrder']);

        $files = $fileCollector->getFiles();

        $maxItems = (int)$this->settings['maxItems'];
        if ($maxItems > 0) {
            return array_slice($files, 0, $maxItems);
        }

        return $files;
    }
}
