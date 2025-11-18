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

namespace Freshworkx\BmImageGallery\Tests\Unit\Controller;

use Freshworkx\BmImageGallery\Controller\GalleryController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Extbase\Mvc\Request;

#[CoversClass(GalleryController::class)]
final class GalleryControllerTest extends TestCase
{
    private GalleryController $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $fileCollectionRepositoryMock = $this->createMock(FileCollectionRepository::class);
        $fileRepositoryMock = $this->createMock(FileRepository::class);
        $loggerMock = $this->createMock(LoggerInterface::class);

        $this->subject = new GalleryController(
            $fileCollectionRepositoryMock,
            $fileRepositoryMock,
            $loggerMock
        );
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function getCurrentPageNumberReturnsOneByDefault(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('hasArgument')->with('currentPageNumber')->willReturn(false);

        $this->injectRequest($requestMock);

        $reflectionMethod = new ReflectionMethod($this->subject, 'getCurrentPageNumber');

        $result = $reflectionMethod->invoke($this->subject);

        self::assertEquals(1, $result);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function getCurrentPageNumberReturnsRequestedPage(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('hasArgument')->with('currentPageNumber')->willReturn(true);
        $requestMock->method('getArgument')->with('currentPageNumber')->willReturn(3);

        $this->injectRequest($requestMock);

        $reflectionMethod = new ReflectionMethod($this->subject, 'getCurrentPageNumber');

        $result = $reflectionMethod->invoke($this->subject);

        self::assertEquals(3, $result);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function getCurrentPageNumberCastsStringToInteger(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('hasArgument')->with('currentPageNumber')->willReturn(true);
        $requestMock->method('getArgument')->with('currentPageNumber')->willReturn('5');

        $this->injectRequest($requestMock);

        $reflectionMethod = new ReflectionMethod($this->subject, 'getCurrentPageNumber');

        $result = $reflectionMethod->invoke($this->subject);

        self::assertIsInt($result);
        self::assertEquals(5, $result);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function getPaginationReturnsArrayPaginatorAndSimplePagination(): void
    {
        $items = ['item1', 'item2', 'item3', 'item4', 'item5'];
        $settings = [
            'pagination' => [
                'itemsPerPage' => 2,
                'maximumNumberOfLinks' => 3,
                'class' => SimplePagination::class,
            ],
        ];

        $this->injectSettings($settings);

        $reflectionMethod = new ReflectionMethod($this->subject, 'getPagination');

        [$paginator, $pagination] = $reflectionMethod->invoke($this->subject, $items, 1);

        self::assertInstanceOf(ArrayPaginator::class, $paginator);
        self::assertInstanceOf(SimplePagination::class, $pagination);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function getPaginationCreatesSlidingWindowPagination(): void
    {
        $items = range(1, 50);
        $settings = [
            'pagination' => [
                'itemsPerPage' => 5,
                'maximumNumberOfLinks' => 7,
                'class' => SlidingWindowPagination::class,
            ],
        ];

        $this->injectSettings($settings);

        $reflectionMethod = new ReflectionMethod($this->subject, 'getPagination');

        [$paginator, $pagination] = $reflectionMethod->invoke($this->subject, $items, 3);

        self::assertInstanceOf(ArrayPaginator::class, $paginator);
        self::assertInstanceOf(SlidingWindowPagination::class, $pagination);
        self::assertEquals(3, $paginator->getCurrentPageNumber());
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function getPaginationFallsBackToSimplePaginationForInvalidClass(): void
    {
        $items = range(1, 10);
        $settings = [
            'pagination' => [
                'itemsPerPage' => 5,
                'class' => 'NonExistentClass',
            ],
        ];

        $this->injectSettings($settings);

        $reflectionMethod = new ReflectionMethod($this->subject, 'getPagination');

        [$paginator, $pagination] = $reflectionMethod->invoke($this->subject, $items, 1);

        // Should fall back to SimplePagination
        self::assertInstanceOf(SimplePagination::class, $pagination);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function getPaginationHandlesEmptyItems(): void
    {
        $items = [];
        $settings = [
            'pagination' => [
                'itemsPerPage' => 10,
            ],
        ];

        $this->injectSettings($settings);

        $reflectionMethod = new ReflectionMethod($this->subject, 'getPagination');

        [$paginator, $pagination] = $reflectionMethod->invoke($this->subject, $items, 1);

        self::assertInstanceOf(ArrayPaginator::class, $paginator);
        self::assertEquals(0, count($paginator->getPaginatedItems())); // @phpstan-ignore argument.type
        self::assertEquals(1, $paginator->getNumberOfPages());
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function getPaginationCalculatesCorrectNumberOfPages(): void
    {
        // 23 items with 5 per page = 5 pages (23 / 5 = 4.6, rounded up to 5)
        $items = range(1, 23);
        $settings = [
            'pagination' => [
                'itemsPerPage' => 5,
            ],
        ];

        $this->injectSettings($settings);

        $reflectionMethod = new ReflectionMethod($this->subject, 'getPagination');

        [$paginator, $pagination] = $reflectionMethod->invoke($this->subject, $items, 1);

        self::assertEquals(5, $paginator->getNumberOfPages());
    }

    /**
     * Helper method to inject request into controller
     */
    private function injectRequest(Request $request): void
    {
        $reflection = new ReflectionClass($this->subject);
        $property = $reflection->getProperty('request');
        $property->setValue($this->subject, $request);
    }

    /**
     * Helper method to inject settings into controller
     * @param array<string, mixed> $settings
     */
    private function injectSettings(array $settings): void
    {
        $reflection = new ReflectionClass($this->subject);
        $property = $reflection->getProperty('settings');
        $property->setValue($this->subject, $settings);
    }
}
