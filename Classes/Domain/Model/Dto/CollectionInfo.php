<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Domain\Model\Dto;

use TYPO3\CMS\Core\Resource\FileInterface;

class CollectionInfo
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $itemCount;

    /**
     * @var FileInterface
     */
    protected $preview;

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier)
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
}
