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

namespace Freshworkx\BmImageGallery\Resource\Collection;

class FolderBasedFileCollection extends \TYPO3\CMS\Core\Resource\Collection\FolderBasedFileCollection
{
    use GalleryTrait;
}
