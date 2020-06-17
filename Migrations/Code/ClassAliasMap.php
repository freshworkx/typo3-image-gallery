<?php

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <f.wessels@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

return [
    '\Bitmotion\BmImageGallery\Controller\ListController' => \Leuchtfeuer\BmImageGallery\Controller\ListController::class,
    '\Bitmotion\BmImageGallery\Domain\Repository\FileCollectionRepository' => \Leuchtfeuer\BmImageGallery\Domain\Repository\FileCollectionRepository::class,
    '\Bitmotion\BmImageGallery\Domain\Transfer\CollectionInfo' => \Leuchtfeuer\BmImageGallery\Domain\Transfer\CollectionInfo::class,
    '\Bitmotion\BmImageGallery\Factory\FileFactory' => \Leuchtfeuer\BmImageGallery\Factory\FileFactory::class,
];
