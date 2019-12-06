<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Factory;

/***
 *
 * This file is part of the "Simple Image Gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Florian Wessels <f.wessels@bitmotion.de>, Bitmotion GmbH
 *
 ***/

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;

class FileFactory
{
    public function getFileObjects(array $fileObjectsToPrepare, int $maxItems = 0): array
    {
        $i = 0;
        $elements = 0;
        $fileObjects = [];
        $maxItems = $this->getMaxItems($maxItems, $fileObjectsToPrepare);

        while ($elements < $maxItems) {
            if (!isset($fileObjectsToPrepare[$i])) {
                // Break when key does not exist
                break;
            }

            $fileObject = $fileObjectsToPrepare[$i];
            $fileObject = $this->transformReference($fileObject);

            if ($this->isTypeSupported($fileObject->getType(), $fileObject->getExtension()) === false) {
                // Type is not supported, continue.
                $i++;
                continue;
            }

            $fileObjects[] = $fileObject;
            $i++;
            $elements++;
        }

        return $fileObjects;
    }

    protected function transformReference($fileObject): File
    {
        if ($fileObject instanceof FileReference) {
            $file = $fileObject->getOriginalFile();

            if ($fileObject->getTitle() || $fileObject->getDescription()) {
                $file->_updateMetaDataProperties([
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
        if ($maxItems === 0) {
            $maxItems = count($fileObjectsToPrepare);
        }

        return $maxItems;
    }

    protected function isTypeSupported(int $type, string $extension): bool
    {
        return $type === 2 || ($type === 4 && ($extension === 'youtube' || $extension === 'vimeo'));
    }
}
