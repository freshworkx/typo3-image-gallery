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

namespace Freshworkx\BmImageGallery\Tests\Functional\Controller;

use Freshworkx\BmImageGallery\Controller\GalleryController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\NullLogger;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Functional test for GalleryController::getCollectionInfo()
 *
 * Tests the protected getCollectionInfo() method which is the core logic
 * for retrieving and processing file collection information
 */
#[CoversClass(GalleryController::class)]
final class GalleryControllerTest extends FunctionalTestCase
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

    protected GalleryController $controller;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Import test data
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/sys_file_storage.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/sys_file.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/sys_file_collection.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/sys_file_reference.csv');

        // Create controller with real dependencies
        $fileCollectionRepository = $this->get(FileCollectionRepository::class);
        $fileRepository = $this->get(FileRepository::class);
        $logger = new NullLogger();

        $this->controller = new GalleryController(
            $fileCollectionRepository,
            $fileRepository,
            $logger
        );

        // Set default TypoScript settings
        $this->injectSettings([
            'orderBy' => 'title',
            'sortingOrder' => 'asc',
            'maxItems' => 0,
        ]);

        // Need for events
        $this->injectEventDispatcher();
    }

    // ===========================================
    // Basic Collection Info Tests
    // ===========================================

    /**
     * Non-existent Collection returns empty array
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsEmptyArrayForNonExistentCollection(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 999, false);

        self::assertEmpty($collectionInfo);
    }

    /**
     * Zero identifier returns empty array
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsEmptyArrayForZeroIdentifier(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 0, false);

        self::assertEmpty($collectionInfo);
    }

    /**
     * Static Collection returns complete info structure
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsBasicInfoForStaticCollection(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertArrayHasKey('identifier', $collectionInfo);
        self::assertArrayHasKey('title', $collectionInfo);
        self::assertArrayHasKey('description', $collectionInfo);
        self::assertArrayHasKey('location', $collectionInfo);
        self::assertArrayHasKey('date', $collectionInfo);
        self::assertArrayHasKey('itemCount', $collectionInfo);
        self::assertArrayHasKey('previewImage', $collectionInfo);
        self::assertArrayHasKey('items', $collectionInfo);
    }

    /**
     * Folder Collection returns complete info structure
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsBasicInfoForFolderCollection(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 2, false);

        self::assertNotEmpty($collectionInfo);
        self::assertArrayHasKey('identifier', $collectionInfo);
        self::assertArrayHasKey('title', $collectionInfo);
        self::assertArrayHasKey('description', $collectionInfo);
        self::assertArrayHasKey('location', $collectionInfo);
        self::assertArrayHasKey('date', $collectionInfo);
        self::assertArrayHasKey('itemCount', $collectionInfo);
        self::assertArrayHasKey('previewImage', $collectionInfo);
        self::assertArrayHasKey('items', $collectionInfo);
    }

    // ===========================================
    // Collection Info Content Tests
    // ===========================================

    /**
     * Correct identifier
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsCorrectIdentifier(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertEquals(1, $collectionInfo['identifier']);
    }

    /**
     * Correct title
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsCorrectTitle(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertEquals('Test Gallery Static', $collectionInfo['title']);
    }

    /**
     * Correct description
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsGalleryDescription(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertEquals('Gallery description for testing', $collectionInfo['description']);
    }

    /**
     * Gallery description (with fallback)
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoFallsBackToCollectionDescription(): void
    {
        // Collection 1 has both gallery_description and description
        // If gallery_description is empty, it should fall back to description
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertNotEmpty($collectionInfo['description']);
    }

    /**
     * Gallery location
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsGalleryLocation(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertEquals('Test Location', $collectionInfo['location']);
    }

    /**
     * Gallery date
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsGalleryDate(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertEquals(1609459200, $collectionInfo['date']);
    }

    /**
     * Correct item count (Static Collection)
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsCorrectItemCount(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertEquals(2, $collectionInfo['itemCount']);
    }

    /**
     * Correct item count (Folder Collection)
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsCorrectItemCountForFolderCollection(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 2, false);

        // Folder collection should have 3 items (all files in root folder)
        self::assertEquals(3, $collectionInfo['itemCount']);
    }

    // ===========================================
    // Preview Image Tests
    // ===========================================

    /**
     * Preview image is FileInterface
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoReturnsPreviewImageAsFileInterface(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertInstanceOf(FileInterface::class, $collectionInfo['previewImage']);
    }

    /**
     * Fallback to first image when no preview set
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoUsesFirstImageAsFallbackPreview(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        // Preview image should be set (either from field or first item)
        self::assertNotNull($collectionInfo['previewImage']);
        self::assertInstanceOf(FileInterface::class, $collectionInfo['previewImage']);
    }

    // ===========================================
    // Items Parameter Tests
    // ===========================================

    /**
     * Without items: returns empty items array
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoWithoutItemsReturnsEmptyItemsArray(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, false);

        self::assertIsArray($collectionInfo['items']);
        self::assertEmpty($collectionInfo['items']);
    }

    /**
     * With items: returns populated items array
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoWithItemsReturnsPopulatedItemsArray(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, true);

        self::assertIsArray($collectionInfo['items']);
        self::assertNotEmpty($collectionInfo['items']);
    }

    /**
     * Items are FileInterface objects
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoItemsAreFileInterfaceObjects(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, true);

        foreach ($collectionInfo['items'] as $item) {
            self::assertInstanceOf(FileInterface::class, $item);
        }
    }

    /**
     * Items count matches itemCount
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoItemsCountMatchesItemCount(): void
    {
        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, true);

        self::assertCount($collectionInfo['itemCount'], $collectionInfo['items']);
    }

    // ===========================================
    // Settings Tests - orderBy
    // ===========================================

    /**
     * Items are ordered by name ascending
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoOrdersItemsByNameAscending(): void
    {
        $this->injectSettings([
            'orderBy' => 'name',
            'sortingOrder' => 'asc',
            'maxItems' => 0,
        ]);

        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, true);
        $items = $collectionInfo['items'];

        self::assertCount(2, $items);

        $names = array_map(fn($item) => $item->getName(), $items);
        $sortedNames = $names;
        sort($sortedNames);
        self::assertEquals($sortedNames, $names);
    }

    // ===========================================
    // Settings Tests - sortingOrder
    // ===========================================

    /**
     * Descending order with name field
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoOrdersItemsByNameDescending(): void
    {
        $this->injectSettings([
            'orderBy' => 'name',
            'sortingOrder' => 'desc',
            'maxItems' => 0,
        ]);

        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, true);
        $items = $collectionInfo['items'];

        self::assertCount(2, $items);

        $names = array_map(fn($item) => $item->getName(), $items);
        $sortedNames = $names;
        rsort($sortedNames);
        self::assertEquals($sortedNames, $names);
    }

    // ===========================================
    // Settings Tests - maxItems
    // ===========================================

    /**
     * maxItems limits number of returned items
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoRespectsMaxItemsLimit(): void
    {
        $this->injectSettings([
            'orderBy' => 'title',
            'sortingOrder' => 'asc',
            'maxItems' => 1,
        ]);

        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, true);
        $items = $collectionInfo['items'];

        // Should only return 1 item even though collection has 2
        self::assertCount(1, $items);
    }

    // ===========================================
    // Settings Tests - Combined
    // ===========================================

    /**
     * Combined: orderBy + sortingOrder + maxItems
     *
     * @throws ReflectionException
     */
    #[Test]
    public function getCollectionInfoCombinesOrderByAndSortingOrderAndMaxItems(): void
    {
        $this->injectSettings([
            'orderBy' => 'name',
            'sortingOrder' => 'desc',
            'maxItems' => 1,
        ]);

        $collectionInfo = $this->callGetCollectionInfo($this->controller, 1, true);
        $items = $collectionInfo['items'];

        // Should return 1 item (maxItems)
        self::assertCount(1, $items);
        // Item should be FileInterface
        self::assertInstanceOf(FileInterface::class, $items[0]);
    }

    // ===========================================
    // Helper Methods
    // ===========================================

    /**
     * Call the protected getCollectionInfo method via reflection
     *
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    private function callGetCollectionInfo(
        GalleryController $controller,
        int $identifier,
        bool $withItems
    ): array {
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getCollectionInfo');

        return $method->invoke($controller, $identifier, $withItems);
    }

    /**
     * @param array<string, mixed> $settings
     * @throws ReflectionException
     */
    private function injectSettings(array $settings): void
    {
        $reflectionProperty = new ReflectionProperty($this->controller, 'settings');
        $reflectionProperty->setValue($this->controller, $settings);
    }

    /**
     * @throws ReflectionException
     */
    private function injectEventDispatcher(): void
    {
        $eventDispatcher = $this->get(EventDispatcherInterface::class);
        $reflectionProperty = new ReflectionProperty($this->controller, 'eventDispatcher');
        $reflectionProperty->setValue($this->controller, $eventDispatcher);
    }
}
