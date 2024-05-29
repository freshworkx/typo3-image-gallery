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

namespace Freshworkx\BmImageGallery\Domain\Transfer;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CollectionInfo
{
    protected int $identifier = 0;

    protected string $title = '';

    protected string $description = '';

    protected int $itemCount = 0;

    protected FileInterface $preview;

    protected ?\DateTimeInterface $date = null;

    protected string $location = '';

    /**
     * @param File[] $fileObjects
     * @throws Exception
     */
    public function __construct(AbstractFileCollection $fileCollection, array $fileObjects)
    {
        $this->setIdentifier($fileCollection->getUid());
        $this->setTitle($fileCollection->getTitle());
        $this->setItemCount(count($fileObjects));
        // TODO: candidate for refactoring!
        //  Why reset $fileObjects?
        //  Reset can be return false, should be avoid in case of $preview => FileInterface
        $this->setPreview(reset($fileObjects)); /** @phpstan-ignore-line */
        $this->loadGalleryData();

        // Consider the original description field of sys_file_collection
        if (empty($this->description)) {
            $this->setDescription($fileCollection->getDescription() ?? ''); /** @phpstan-ignore-line */
        }
    }

    public function getIdentifier(): int
    {
        return $this->identifier;
    }

    public function setIdentifier(int $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getItemCount(): int
    {
        return $this->itemCount;
    }

    public function setItemCount(int $itemCount): void
    {
        $this->itemCount = $itemCount;
    }

    public function getPreview(): FileInterface
    {
        return $this->preview;
    }

    public function setPreview(FileInterface $preview): void
    {
        $this->preview = $preview;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @throws Exception
     */
    protected function loadGalleryData(): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_collection');

        $properties = $queryBuilder
            ->select('bm_image_gallery_description', 'bm_image_gallery_location', 'bm_image_gallery_date')
            ->from('sys_file_collection')
            ->where($queryBuilder->expr()->eq(
                'uid',
                $queryBuilder->createNamedParameter($this->identifier, Connection::PARAM_INT)
            ))
            ->executeQuery()
            ->fetchAssociative();

        $this->setDescription($properties['bm_image_gallery_description'] ?? '');
        $this->setLocation($properties['bm_image_gallery_location'] ?? '');

        if (($properties['bm_image_gallery_date'] ?? 0) > 0) {
            $this->setDate((new \DateTime())->setTimestamp($properties['bm_image_gallery_date']));
        }
    }
}
