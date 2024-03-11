<?php

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Dev <dev@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

use Freshworkx\BmImageGallery\Controller\ListController;
use Freshworkx\BmImageGallery\Domain\Repository\FileCollectionRepository;
use Freshworkx\BmImageGallery\Domain\Transfer\CollectionInfo;
use Freshworkx\BmImageGallery\Factory\FileFactory;

return [
    '\Leuchtfeuer\BmImageGallery\Controller\ListController' => ListController::class,
    '\Leuchtfeuer\BmImageGallery\Domain\Repository\FileCollectionRepository' => FileCollectionRepository::class,
    '\Leuchtfeuer\BmImageGallery\Domain\Transfer\CollectionInfo' => CollectionInfo::class,
    '\Leuchtfeuer\BmImageGallery\Factory\FileFactory' => FileFactory::class,
];
