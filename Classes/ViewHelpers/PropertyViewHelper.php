<?php
namespace Bitmotion\BmImageGallery\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Rene Fritz <typo3-ext@bitmotion.de>, Bitmotion
 *  (c) 2016 Florian Wessels <typo3-ext@bitmotion.de>, Bitmotion
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class PropertyViewHelper
 * @package Bitmotion\BmImageGallery\ViewHelpers
 */
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
