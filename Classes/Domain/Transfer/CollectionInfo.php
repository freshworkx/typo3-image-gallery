<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Domain\Transfer;

/***
 *
 * This file is part of the "Simple Image Gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Florian Wessels <f.wessels@bitmotion.de>, Bitmotion GmbH
 *
 ***/

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CollectionInfo
{
    protected $identifier;

    protected $title;

    protected $description;

    protected $itemCount;

    protected $preview;

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

    public function setIdentifier(int $identifier)
    {
        $this->identifier = $identifier;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getItemCount(): int
    {
        return $this->itemCount;
    }

    public function setItemCount(int $itemCount)
    {
        $this->itemCount = $itemCount;
    }

    public function getPreview(): FileInterface
    {
        return $this->preview;
    }

    public function setPreview(FileInterface $preview)
    {
        $this->preview = $preview;
    }

    protected function getRichTextDescription()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_collection');

        return $queryBuilder
            ->select('bm_image_gallery_description')
            ->from('sys_file_collection')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($this->identifier, \PDO::PARAM_INT)))
            ->execute()
            ->fetchColumn(0);
    }
}
