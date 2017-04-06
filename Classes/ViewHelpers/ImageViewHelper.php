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
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class ImageViewHelper
 * @package Bitmotion\BmImageGallery\ViewHelpers
 */
class ImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $contentObject;

    /**
     * @var string
     */
    protected $tagName = 'img';

    /**
     * @var TypoScriptFrontendController contains a backup of the current $GLOBALS['TSFE'] if used in BE mode
     */
    protected $tsfeBackup;

    /**
     * @var string
     */
    protected $workingDirectoryBackup;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     *
     * @return void
     */
    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
    ) {
        $this->configurationManager = $configurationManager;
        $this->contentObject = $this->configurationManager->getContentObject();
    }

    /**
     * Initialize arguments.
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('alt', 'string', 'Specifies an alternate text for an image', false);
        $this->registerTagAttribute('ismap', 'string',
            'Specifies an image as a server-side image-map. Rarely used. Look at usemap instead', false);
        $this->registerTagAttribute('longdesc', 'string',
            'Specifies the URL to a document that contains a long description of an image', false);
        $this->registerTagAttribute('usemap', 'string', 'Specifies an image as a client-side image-map', false);
    }

    /**
     * Resizes a given image (if required) and renders the respective img tag
     *
     * @see http://typo3.org/documentation/document-library/references/doc_core_tsref/4.2.0/view/1/5/#id4164427
     *
     * @param \TYPO3\CMS\Core\Resource\ResourceInterface $file
     * @param string $width width of the image. This can be a numeric value
     *     representing the fixed width of the image in pixels. But you can also perform simple calculations by adding
     *     "m" or "c" to the value. See imgResource.width for possible options.
     * @param string $height height of the image. This can be a numeric value
     *     representing the fixed height of the image in pixels. But you can also perform simple calculations by adding
     *     "m" or "c" to the value. See imgResource.width for possible options.
     * @param integer $minWidth minimum width of the image
     * @param integer $minHeight minimum height of the image
     * @param integer $maxWidth maximum width of the image
     * @param integer $maxHeight maximum height of the image
     * @param boolean $includeCopyright
     * @param boolean $includeClickEnlarge
     * @param boolean $includeDescription
     *
     * @return string rendered tag.
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function render(
        $file,
        $width = null,
        $height = null,
        $minWidth = null,
        $minHeight = null,
        $maxWidth = null,
        $maxHeight = null,
        $includeCopyright = false,
        $includeClickEnlarge = false,
        $includeDescription = false
    ) {
        $completeHtml = '';
        /** @var $file \TYPO3\CMS\Core\Resource\ResourceInterface */
        if (!is_object($file)) {
            return '';
        }

        if ($file instanceof \TYPO3\CMS\Core\Resource\FileReference) {
            $file = $file->getOriginalFile();
        }


        $processingConfiguration = [
            'width' => $width,
            'height' => $height,
            'minWidth' => $minWidth,
            'minHeight' => $minHeight,
            'maxWidth' => $maxWidth,
            'maxHeight' => $maxHeight,
        ];

        $processedFileObject = $file->process(\TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGECROPSCALEMASK,
            $processingConfiguration);
        if (!is_object($processedFileObject)) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('Could not get image resource for "' . $file->getPublicUrl() . '".',
                1253191060);
        }

        $imageSource = $processedFileObject->getPublicUrl();

        if (TYPO3_MODE === 'BE') {
            $imageSource = '../' . $imageSource;
        }

        $this->tag->addAttribute('src', $imageSource);
        $this->tag->addAttribute('width', $processedFileObject->getProperty('width'));
        $this->tag->addAttribute('height', $processedFileObject->getProperty('height'));
        if (empty($this->arguments['title'])) {
            $this->tag->addAttribute('title', $file->getProperty('title'));
        }

        if (empty($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', $file->getProperty('alternative'));
        }

        if (!$includeCopyright && !$includeClickEnlarge) {
            $completeHtml = $this->tag->render();
        } else {
            $imageTag = $this->tag->render();
            if ($includeCopyright) {
                $completeHtml = '<span class="image-wrapper-copyright">' . $file->getProperty('assets_copyright') . '</span>' . $imageTag;
            }
            if ($includeClickEnlarge) {
                $completeHtml = '<span class="image-wrapper-enlarge"><img src="/fileadmin/templates/images/icon-loupe.png" alt="" title="Bild vergr&ouml;&szlig;ern" /></span>' . ($completeHtml ?: $imageTag);
            }
            $completeHtml = '<span class="image-wrapper">' . $completeHtml . '</span>';
        }

        if ($includeDescription) {
            $description = trim($file->getProperty('description'));
            if (!empty($description)) {
                $completeHtml = $completeHtml . '<span class="image-description">' . nl2br($file->getProperty('description')) . '</span>';
            }
        }

        return $completeHtml;
    }

    /**
     * Prepares $GLOBALS['TSFE'] for Backend mode
     * This somewhat hacky work around is currently needed because the getImgResource() function of tslib_cObj relies
     * on those variables to be set
     *
     * @return void
     */
    protected function simulateFrontendEnvironment()
    {
        $this->tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
        // set the working directory to the site root
        $this->workingDirectoryBackup = getcwd();
        chdir(PATH_site);
        $typoScriptSetup = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $GLOBALS['TSFE'] = new \stdClass();
        $template = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
        $template->tt_track = 0;
        $template->init();
        $template->getFileName_backPath = PATH_site;
        $GLOBALS['TSFE']->tmpl = $template;
        $GLOBALS['TSFE']->tmpl->setup = $typoScriptSetup;
        $GLOBALS['TSFE']->config = $typoScriptSetup;
    }

    /**
     * Resets $GLOBALS['TSFE'] if it was previously changed by simulateFrontendEnvironment()
     *
     * @return void
     * @see simulateFrontendEnvironment()
     */
    protected function resetFrontendEnvironment()
    {
        $GLOBALS['TSFE'] = $this->tsfeBackup;
        chdir($this->workingDirectoryBackup);
    }

}


?>
