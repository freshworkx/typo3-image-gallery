<?php

declare(strict_types=1);

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <f.wessels@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

namespace Leuchtfeuer\BmImageGallery\Domain\Transfer;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CollectionInfo
{
    protected $identifier = 0;

    protected $title = '';

    protected $description = '';

    protected $itemCount = 0;

    protected $preview;

    /**
     * @var string
     */
    protected $richTextDescription;

    public function __construct(AbstractFileCollection $fileCollection, array $fileObjects)
    {
        $this->setIdentifier($fileCollection->getUid());
        $this->setTitle((string)$fileCollection->getTitle());
        $this->setDescription($this->getRichTextDescription() ?? (string)$fileCollection->getDescription());
        $this->setItemCount(count($fileObjects));
        $this->setPreview(reset($fileObjects));
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

    protected function getRichTextDescription(): string
    {
        return $this->richTextDescription ?? $this->setRichTextDescription();
    }

    protected function setRichTextDescription(): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_collection');

        $galleryDescription = (string)$queryBuilder
            ->select('bm_image_gallery_description')
            ->from('sys_file_collection')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($this->identifier, \PDO::PARAM_INT)))
            ->execute()
            ->fetchColumn(0);

        $this->richTextDescription = $galleryDescription;

        return $galleryDescription;
    }
}
