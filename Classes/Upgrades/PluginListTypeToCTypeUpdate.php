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

namespace Freshworkx\BmImageGallery\Upgrades;

use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;

#[UpgradeWizard('bmImageGalleryPluginListTypeToCTypeUpdate')]
final class PluginListTypeToCTypeUpdate extends AbstractListTypeToCTypeUpdate
{
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'bmimagegallery_gallerylist' => 'bmimagegallery_gallerylist',
            'bmimagegallery_gallerydetail' => 'bmimagegallery_gallerydetail',
            'bmimagegallery_selectedgallery' => 'bmimagegallery_selectedgallery',
        ];
    }

    public function getTitle(): string
    {
        return 'Migrates bm_image_gallery plugins';
    }

    public function getDescription(): string
    {
        return 'Migrates bm_image_gallery plugins from list_type to CType.';
    }
}
