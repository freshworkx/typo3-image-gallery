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

namespace Freshworkx\BmImageGallery\Resource\Collection;

trait GalleryTrait
{
    protected string $galleryDescription = '';

    protected string $galleryLocation = '';

    protected int $galleryDate = 0;

    protected int $galleryPreviewImage = 0;

    protected function getPersistableDataArray(): array
    {
        $dataArray = parent::getPersistableDataArray();
        $dataArray['bm_image_gallery_description'] = $this->galleryDescription;
        $dataArray['bm_image_gallery_location'] = $this->galleryLocation;
        $dataArray['bm_image_gallery_date'] = $this->galleryDate;
        $dataArray['bm_image_gallery_preview_image'] = $this->galleryPreviewImage;

        return $dataArray;
    }

    public function fromArray(array $array): void
    {
        parent::fromArray($array);
        $this->galleryDescription = $array['bm_image_gallery_description'] ?? '';
        $this->galleryLocation = $array['bm_image_gallery_location'] ?? '';
        $this->galleryDate = $array['bm_image_gallery_date'] ?? 0;
        $this->galleryPreviewImage = $array['bm_image_gallery_preview_image'] ?? 0;
    }

    public function getGalleryDescription(): string
    {
        return $this->galleryDescription;
    }

    public function setGalleryDescription(string $galleryDescription): void
    {
        $this->galleryDescription = $galleryDescription;
    }

    public function getGalleryLocation(): string
    {
        return $this->galleryLocation;
    }

    public function setGalleryLocation(string $galleryLocation): void
    {
        $this->galleryLocation = $galleryLocation;
    }

    public function getGalleryDate(): int
    {
        return $this->galleryDate;
    }

    public function setGalleryDate(int $galleryDate): void
    {
        $this->galleryDate = $galleryDate;
    }

    public function getGalleryPreviewImage(): int
    {
        return $this->galleryPreviewImage;
    }

    public function setGalleryPreviewImage(int $galleryPreviewImage): void
    {
        $this->galleryPreviewImage = $galleryPreviewImage;
    }
}