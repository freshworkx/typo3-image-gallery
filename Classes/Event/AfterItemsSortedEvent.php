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

use TYPO3\CMS\Core\Resource\FileInterface;

/**
 * Event fired after items have been sorted and limited.
 *
 * This event allows listeners to:
 * - Filter items based on custom criteria
 * - Enrich items with additional metadata
 * - Apply post-processing to items
 * - Re-sort items with custom logic
 * - Add or remove items from the final list
 */
final class AfterItemsSortedEvent
{
    /**
     * @param array<int, FileInterface> $items
     * @param array<string, mixed> $settings
     */
    public function __construct(
        private array $items,
        private readonly array $settings
    ) {
    }

    /**
     * Get the sorted and limited items.
     *
     * @return array<int, FileInterface>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Set the items array.
     * Use this to filter, modify, or replace the items.
     *
     * @param array<int, FileInterface> $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * Get the extension settings from TypoScript.
     *
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        return $this->settings;
    }
}
