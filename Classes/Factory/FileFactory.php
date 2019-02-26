<?php
declare(strict_types=1);
namespace Bitmotion\BmImageGallery\Factory;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;

class FileFactory
{
    public function getFileObjects(array $fileObjectsToPrepare, int $maxItems = 0): array
    {
        if ($maxItems === 0) {
            $maxItems = count($fileObjectsToPrepare);
        }

        $i = 0;
        $elements = 0;
        $fileObjects = [];

        while ($elements < $maxItems) {
            if (!isset($fileObjectsToPrepare[$i])) {
                // Break when key does not exist
                break;
            }

            $fileObject = $fileObjectsToPrepare[$i];
            $fileObject = $this->transformReference($fileObject);
            $type = $fileObject->getType();
            $extension = $fileObject->getExtension();

            if (!($type === 2 || ($type === 4 && ($extension === 'youtube' || $extension === 'vimeo')))) {
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
}
