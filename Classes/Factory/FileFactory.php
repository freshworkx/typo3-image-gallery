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

namespace Freshworkx\BmImageGallery\Factory;

use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;

class FileFactory
{
    /**
     * @param File[]|FileReference[] $fileObjectsToPrepare
     * @param int $maxItems
     *
     * @return File[]
     */
    public function getFileObjects(array $fileObjectsToPrepare, int $maxItems = 0): array
    {
        $maxItems = $this->getMaxItems($maxItems, $fileObjectsToPrepare);
        $files = [];

        foreach ($fileObjectsToPrepare as $fileObjectToPrepare) {
            $file = $this->transformReference($fileObjectToPrepare);

            if ($this->isTypeSupported($file->getType(), $file->getExtension()) === false) {
                // Type is not supported, continue.
                continue;
            }

            $files[] = $file;

            if (count($files) === $maxItems) {
                break;
            }
        }

        return $files;
    }

    /**
     * @param File|FileReference $fileObject
     *
     * @return File
     */
    protected function transformReference($fileObject): File
    {
        if ($fileObject instanceof FileReference) {
            $file = $fileObject->getOriginalFile();

            if ($fileObject->getTitle() || $fileObject->getDescription()) {
                $file->updateProperties([
                    'title' => $fileObject->getTitle(),
                    'description' => $fileObject->getDescription(),
                ]);
            }

            return $file;
        }

        return $fileObject;
    }

    protected function getMaxItems(int $maxItems, array $fileObjectsToPrepare): int
    {
        return $maxItems === 0 ? count($fileObjectsToPrepare) : $maxItems;
    }

    protected function isTypeSupported(int $type, string $extension): bool
    {
        return $type === AbstractFile::FILETYPE_IMAGE ||
            ($type === AbstractFile::FILETYPE_VIDEO && ($extension === 'youtube' || $extension === 'vimeo'));
    }
}
