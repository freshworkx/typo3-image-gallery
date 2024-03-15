<?php

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Jens Neumann <info@freshworkx.de>
 */

namespace {
    die('Access denied');
}

namespace Leuchtfeuer\BmImageGallery\Controller {
    // @deprecated since v6.1, will be removed in v7.0
    class ListController extends \Freshworkx\BmImageGallery\Controller\ListController
    {
    }
}

namespace Leuchtfeuer\BmImageGallery\Domain\Repository {
    // @deprecated since v6.1, will be removed in v7.0
    class FileCollectionRepository extends \Freshworkx\BmImageGallery\Domain\Repository\FileCollectionRepository
    {
    }
}

namespace Leuchtfeuer\BmImageGallery\Domain\Transfer {
    // @deprecated since v6.1, will be removed in v7.0
    class CollectionInfo extends \Freshworkx\BmImageGallery\Domain\Transfer\CollectionInfo
    {
    }
}

namespace Leuchtfeuer\BmImageGallery\Factory {
    // @deprecated since v6.1, will be removed in v7.0
    class FileFactory extends \Freshworkx\BmImageGallery\Factory\FileFactory
    {
    }
}
