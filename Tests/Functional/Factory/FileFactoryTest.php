<?php

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Dev <dev@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

namespace Freshworkx\BmImageGallery\Tests\Functional\Factory;

use Freshworkx\BmImageGallery\Factory\FileFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \Freshworkx\BmImageGallery\Factory\FileFactory
 */
class FileFactoryTest extends FunctionalTestCase
{
    protected $subject;

    protected $testExtensionsToLoad = [
        'typo3conf/ext/bm_image_gallery',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = GeneralUtility::makeInstance(FileFactory::class);
    }
}
