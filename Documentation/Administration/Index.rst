.. include:: ../Includes.txt

.. _admin:

==================
For Administrators
==================

Installation
============

If you are in composer mode, use `composer req bitmotion/bm-image-gallery`.

The extension is available in the `TYPO3 Extension Repository (TER) <https://extensions.typo3.org/extension/bm_image_gallery/>`__
and can also be installed via Extension Manager. Ensure, that the static templates are included.

Configuration
=============

Templates
---------

Set alternative Layout/Template/Partial path individually to use your own Fluid templates. Simply set the following TypoScript
constants:

.. code-block:: typoscript

   plugin.tx_bmimagegallery.view {
       templateRootPath = EXT:your_ext/Resources/Private/Template/Path/
       partialRootPath = EXT:your_ext/Resources/Private/Partial/Path/
       layoutRootPath = EXT:your_ext/Resources/Private/Layout/Path/
   }

Settings
--------

These settings may be overwritten in your TypoScript:

.. code-block:: typoscript

   plugin.tx_bmimagegallery.settings {
       # Show the number of file collections. 1 means TRUE, 0 means FALSE.
       list.showCount = 1
       gallery {
           # Show the number of files in a gallery. 1 means TRUE, 0 means FALSE.
           showCount = 1
           # Show a description of a file collection in gallery mode. 1 means TRUE, 0 means FALSE.
           showDescription = 1
       }
       videos {
           # Append params for YouTube videos.
           youtube.params = autoplay=1&fs=1
           # Append params for Vimeo videos.
           vimeo.params = color=000
       }

       images {
           # Here you can set image sizes.
           width = 300c
           maxWidth = 500
           height = 300c
           maxHeight = 500
       }

       lightbox {
           # 'cssClass' and 'relAttribute' are not predefined in the constants
           # by default but in the Fluid Templates.
           cssClass = {$styles.content.textmedia.linkWrap.lightboxEnabled}
           relAttribute = {$styles.content.textmedia.linkWrap.lightboxCssClass}
       }
   }

Templating
==========

If you want to change the Fluid templates or use your own, copy the original files with the complete folder structure, e.g. into
your site package and set the TypoScript configuration as shown above. If TYPO3 finds a fluid template file under the given
alternative path, it will use this, otherwise the original files in the extension.



