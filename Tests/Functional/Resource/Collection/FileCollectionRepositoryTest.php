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

namespace Freshworkx\BmImageGallery\Tests\Functional\Resource\Collection;

use Freshworkx\BmImageGallery\Resource\Collection\CategoryBasedFileCollection;
use Freshworkx\BmImageGallery\Resource\Collection\FolderBasedFileCollection;
use Freshworkx\BmImageGallery\Resource\Collection\StaticFileCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Functional test for FileCollection functionality
 *
 * Tests repository operations, collection loading, and custom gallery properties
 */
#[CoversClass(FolderBasedFileCollection::class)]
#[CoversClass(StaticFileCollection::class)]
#[CoversClass(CategoryBasedFileCollection::class)]
final class FileCollectionRepositoryTest extends FunctionalTestCase
{
    /**
     * Test extensions to load - use composer package name
     */
    protected array $testExtensionsToLoad = [
        'freshworkx/bm-image-gallery',
    ];

    /**
     * Provide test files to test instance
     * Paths are relative to the test instance root
     */
    protected array $pathsToProvideInTestInstance = [
        'typo3conf/ext/bm_image_gallery/Tests/Functional/Fixtures/fileadmin' => 'fileadmin',
    ];

    protected FileCollectionRepository $fileCollectionRepository;

    protected FileRepository $fileRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Import test data
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/sys_file_storage.csv');
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/sys_file.csv');
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/sys_file_metadata.csv');
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/sys_category.csv');
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/sys_category_record_mm.csv');
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/sys_file_collection.csv');
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/sys_file_reference.csv');

        // Get repositories from container
        $this->fileCollectionRepository = $this->get(FileCollectionRepository::class);
        $this->fileRepository = $this->get(FileRepository::class);
    }

    // ===========================================
    // Collection Loading Tests
    // ===========================================

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function staticFileCollectionCanBeLoaded(): void
    {
        $collection = $this->fileCollectionRepository->findByUid(1);

        self::assertInstanceOf(StaticFileCollection::class, $collection);
        self::assertEquals('Test Gallery Static', $collection->getTitle());
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function folderFileCollectionCanBeLoaded(): void
    {
        $collection = $this->fileCollectionRepository->findByUid(2);

        self::assertInstanceOf(FolderBasedFileCollection::class, $collection);
        self::assertEquals('Test Gallery Folder', $collection->getTitle());
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function categoryFileCollectionCanBeLoaded(): void
    {
        $collection = $this->fileCollectionRepository->findByUid(3);

        self::assertInstanceOf(CategoryBasedFileCollection::class, $collection);
        self::assertEquals('Test Gallery Category', $collection->getTitle());
    }

    #[Test]
    public function nonExistentCollectionThrowsException(): void
    {
        $this->expectException(ResourceDoesNotExistException::class);
        $this->expectExceptionMessage('Could not find row with uid "999" in table "sys_file_collection"');

        $this->fileCollectionRepository->findByUid(999);
    }

    // ===========================================
    // Collection Content Tests
    // ===========================================

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function staticCollectionCanLoadContents(): void
    {
        /** @var StaticFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(1);
        $collection->loadContents();

        $items = $collection->getItems();

        self::assertCount(2, $items);
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function folderCollectionCanLoadContents(): void
    {
        /** @var FolderBasedFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(2);
        $collection->loadContents();

        $items = $collection->getItems();

        self::assertCount(3, $items);
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function categoryCollectionCanLoadContents(): void
    {
        /** @var CategoryBasedFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(3);
        $collection->loadContents();

        $items = $collection->getItems();

        // Files with category 1: files 1 and 2
        self::assertCount(2, $items);
    }

    // ===========================================
    // Gallery Property Tests - Static Collection
    // ===========================================

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function staticCollectionGalleryDescriptionCanBeRetrieved(): void
    {
        /** @var StaticFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(1);

        self::assertEquals('Gallery description for testing', $collection->getGalleryDescription());
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function staticCollectionGalleryLocationCanBeRetrieved(): void
    {
        /** @var StaticFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(1);

        self::assertEquals('Test Location', $collection->getGalleryLocation());
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function staticCollectionGalleryDateCanBeRetrieved(): void
    {
        /** @var StaticFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(1);

        self::assertEquals(1609459200, $collection->getGalleryDate());
    }

    // ===========================================
    // Gallery Property Tests - Folder Collection
    // ===========================================

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function folderCollectionGalleryDescriptionCanBeRetrieved(): void
    {
        /** @var FolderBasedFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(2);

        self::assertEquals('Folder gallery description', $collection->getGalleryDescription());
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function folderCollectionGalleryLocationCanBeRetrieved(): void
    {
        /** @var FolderBasedFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(2);

        self::assertEquals('Berlin', $collection->getGalleryLocation());
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function folderCollectionGalleryDateCanBeRetrieved(): void
    {
        /** @var FolderBasedFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(2);

        self::assertEquals(1612137600, $collection->getGalleryDate());
    }

    // ===========================================
    // Gallery Property Tests - Category Collection
    // ===========================================

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function categoryCollectionGalleryDescriptionCanBeRetrieved(): void
    {
        /** @var CategoryBasedFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(3);

        self::assertEquals('Category gallery description', $collection->getGalleryDescription());
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function categoryCollectionGalleryLocationCanBeRetrieved(): void
    {
        /** @var CategoryBasedFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(3);

        self::assertEquals('Munich', $collection->getGalleryLocation());
    }

    /**
     * @throws ResourceDoesNotExistException
     */
    #[Test]
    public function categoryCollectionGalleryDateCanBeRetrieved(): void
    {
        /** @var CategoryBasedFileCollection $collection */
        $collection = $this->fileCollectionRepository->findByUid(3);

        self::assertEquals(1640995200, $collection->getGalleryDate());
    }
}
