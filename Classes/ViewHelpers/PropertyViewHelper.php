<?php
namespace Bitmotion\BmImageGallery\ViewHelpers;

    /*                                                                        *
     * This script is part of the TYPO3 project - inspiring people to share!  *
     *                                                                        *
     * TYPO3 is free software; you can redistribute it and/or modify it under *
     * the terms of the GNU General Public License version 2 as published by  *
     * the Free Software Foundation.                                          *
     *                                                                        *
     * This script is distributed in the hope that it will be useful, but     *
     * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
     * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
     * Public License for more details.                                       *
     *                                                                        */

class PropertyViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{


    /**
     * Returns the value of an objects property using $object->getProperty()
     *
     * @param object $object
     * @param string $property name of the property to be fetched using $object->getProperty()
     *
     * @return string rendered tag.
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function render($object, $property)
    {
        /** @var $file \TYPO3\CMS\Core\Resource\ResourceInterface */
        if (!is_object($object) OR !$property) {
            return '';
        }

        return $object->getProperty($property);
    }


}
