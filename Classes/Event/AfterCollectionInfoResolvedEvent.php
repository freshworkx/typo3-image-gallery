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

namespace Freshworkx\BmImageGallery\Event;

use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;

/**
 * Event fired after collection information has been fully resolved.
 *
 * This event allows listeners to:
 * - Add custom fields to the collection info array
 * - Modify existing collection info fields
 * - Add computed properties (e.g., hasMultipleImages, totalSize)
 * - Enrich data with external system information
 * - Add formatted date fields
 * - Add SEO metadata
 */
final class AfterCollectionInfoResolvedEvent
{
    /**
     * @param array<string, mixed> $collectionInfo
     */
    public function __construct(
        private array $collectionInfo,
        private readonly AbstractFileCollection $fileCollection,
        private readonly int $identifier
    ) {
    }

    /**
     * Get the collection information array.
     *
     * @return array<string, mixed>
     */
    public function getCollectionInfo(): array
    {
        return $this->collectionInfo;
    }

    /**
     * Set the collection information array.
     * Use this to add custom fields or modify existing ones.
     *
     * @param array<string, mixed> $collectionInfo
     */
    public function setCollectionInfo(array $collectionInfo): void
    {
        $this->collectionInfo = $collectionInfo;
    }

    /**
     * Get the original file collection object.
     */
    public function getFileCollection(): AbstractFileCollection
    {
        return $this->fileCollection;
    }

    /**
     * Get the originally requested collection identifier.
     */
    public function getIdentifier(): int
    {
        return $this->identifier;
    }
}
