<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Florian Wessels <typo3-ext@bitmotion.de>, Bitmotion
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use Bitmotion\BmImageGallery\Domain\Model\Dto\CollectionInfo;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Resource\FileCollector;

/**
 * Class ListController
 */
class ListController extends ActionController
{
    /**
     * @var \TYPO3\CMS\Core\Resource\FileCollectionRepository
     */
    protected $fileCollectionRepository = null;

    public function injectFileCollectionRepository(FileCollectionRepository $fileCollectionRepository)
    {
        $this->fileCollectionRepository = $fileCollectionRepository;
    }

    /**
     * action default
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
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

    /**
     * @param string $collections
     * @return array
     */
    protected function getCollectionsToDisplay($collections)
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

    /**
     * action overview
     *
     * @return void
     */
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

                    $collectionInfo = new CollectionInfo();
                    $collectionInfo->setIdentifier($collectionUid);
                    $collectionInfo->setTitle($fileCollection->getTitle());
                    $collectionInfo->setDescription($fileCollection->getDescription());
                    $collectionInfo->setItemCount(count($fileObjects));
                    /** @var File $fileObject */
                    $fileObject = reset($fileObjects);
                    $collectionInfo->setPreview($fileObject);

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
     * action list
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function listAction()
    {
        $fileObjects = [];
        $fileCollection = '';

        $collectionUids = $this->getCollectionsToDisplay($this->settings['collections']);
        $collectionUidsCount = count($collectionUids);

        if ($collectionUidsCount > 0) {
            if ($this->request->hasArgument('show')) {
                $showUid = $this->request->getArgument('show');

                if (in_array($showUid, $collectionUids)) {
                    $collectionUids = [$showUid];
                }
            }

            /** @var FileCollector $fileCollector */
            $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
            $fileCollector->addFilesFromFileCollections($collectionUids);
            if ($this->settings['orderBy'] === '' || $this->settings['orderBy'] !== 'default') {
                $fileCollector->sort($this->settings['orderBy'], ($this->settings['sortingOrder'] ? $this->settings['sortingOrder'] : 'ascending'));
            }

            $fileObjects = $fileCollector->getFiles();

            if ($this->settings['maxItems'] > 0) {
                $fileObjects = array_slice($fileObjects, 0, $this->settings['maxItems']);
            }

            $fileCollection = $this->fileCollectionRepository->findByUid($collectionUids[count($collectionUids) - 1]);

            foreach ($fileObjects as $key => $fileReference) {
                // file collection returns different types depending on the static or folder type
                if ($fileReference instanceof FileReference) {
                    /** @var File $file */
                    $file = $fileReference->getOriginalFile();

                    // update metadata of original file - handle metadata overrides
                    if ($fileReference->getTitle() || $fileReference->getDescription()) {
                        $file->_updateMetaDataProperties([
                            'title' => $fileReference->getTitle(),
                            'description' => $fileReference->getDescription(),
                        ]);
                    }

                    $fileObjects[$key] = $file;
                }
            }

            if (method_exists($this->configurationManager, 'getContentObjectRenderer')) {
                $contentId = $this->configurationManager->getContentObjectRenderer()->data['uid'];
            } else {
                // TODO: Remove this when we drop TYPO3 7 LTS support
                $contentId = $this->configurationManager->getContentObject()->data['uid'];
            }

            $this->view->assignMultiple([
                'contentId' => $contentId,
                'collectionCount' => $collectionUidsCount,
                'title' => $fileCollection->getTitle(),
                'description' => $fileCollection->getDescription(),
                'itemCount' => count($fileObjects),
                'items' => $fileObjects,
            ]);
        } else {
            $this->forward('overview');
        }
    }

    /**
     * Adds $items to $array, which is passed by reference. Array must only consist of numerical keys.
     *
     * @param mixed $items  Array with new items or single object that's added.
     * @param array $array  The array the new items should be added to. Must only contain numeric keys (for
     *                      array_merge() to add items instead of replacing).
     */
    protected function addToArray($items, array &$array)
    {
        if (is_array($items)) {
            $array = array_merge($array, $items);
        } elseif (is_object($items)) {
            $array[] = $items;
        }
    }
}
