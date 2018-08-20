<?php
namespace Bitmotion\BmImageGallery\Domain\Model\Dto;

/***************************************************************
 *  Copyright notice
 *
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
use TYPO3\CMS\Core\Resource\FileInterface;

/**
 * Class CollectionInfo
 * @package Bitmotion\BmImageGallery\Utility
 */
class CollectionInfo
{

    /**
     * @var string
     */
    protected $identifier = '';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var integer
     */
    protected $itemCount = 0;

    /**
     * @var \TYPO3\CMS\Core\Resource\FileInterface
     */
    protected $preview = null;

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getItemCount()
    {
        return $this->itemCount;
    }

    /**
     * @param int $itemCount
     */
    public function setItemCount($itemCount)
    {
        $this->itemCount = $itemCount;
    }

    /**
     * @return FileInterface
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param FileInterface $preview
     */
    public function setPreview(FileInterface $preview)
    {
        $this->preview = $preview;
    }

}
