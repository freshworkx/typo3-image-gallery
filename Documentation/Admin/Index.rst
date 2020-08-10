.. include:: ../Includes.txt

.. _admin:

==================
For Administrators
==================

.. _admin-installation:

Installation
============

If you are in composer mode, use `composer req leuchtfeuer/bm-image-gallery`.

The extension is available in the `TYPO3 Extension Repository (TER) <https://extensions.typo3.org/extension/bm_image_gallery/>`__
and can also be installed via Extension Manager. Ensure, that the static templates are included.

.. _admin-templates:

Templates
=========

Set alternative Layout/Template/Partial path individually to use your own Fluid templates. Simply set the following TypoScript
constants:

.. code-block:: typoscript

   plugin.tx_bmimagegallery.view {
       templateRootPath = EXT:your_ext/Resources/Private/Template/Path/
       partialRootPath = EXT:your_ext/Resources/Private/Partial/Path/
       layoutRootPath = EXT:your_ext/Resources/Private/Layout/Path/
   }

.. _admin-configuration:

Configuration
=============

The following configuration may be overwritten in your TypoScript constants.

.. _admin-configuration-list:

List
----

Show the number of file collections in the gallery list view. 1 means TRUE, 0 means FALSE.

.. code-block:: typoscript

   plugin.tx_bmimagegallery {
       settings {
           list {
               showCount = 1
           }
       }
   }

.. _admin-configuration-gallery:

Gallery
-------

You can configure whether to display the amount of files given in your file collection or whether to show the gallery description
of the file collection.

.. code-block:: typoscript

   plugin.tx_bmimagegallery {
       settings {
           gallery {
               # Show the number of files in a gallery. 1 means TRUE, 0 means FALSE.
               showCount = 1

               # Show a description of a file collection in gallery mode. 1 means TRUE, 0 means FALSE.
               showDescription = 1
           }
       }
   }


.. _admin-configuration-videos:

Videos
------

You can add some custom parameters to the URI where the videos will be retrieved from.

.. code-block:: typoscript

   plugin.tx_bmimagegallery {
       settings {
           videos {
               # Append params for YouTube videos.
               youtube.params = autoplay=1&fs=1

               # Append params for Vimeo videos.
               vimeo.params = color=000
           }
       }
   }


.. _admin-configuration-images:

Images
------

Here you can set default image sizes.

.. code-block:: typoscript

   plugin.tx_bmimagegallery {
       settings {
           images {
               width = 300c
               maxWidth = 500
               height = 300c
               maxHeight = 500
           }
       }
   }

.. _admin-configuration-lightbox:

Lightbox
--------

.. code-block:: typoscript

   plugin.tx_bmimagegallery {
       settings {
           lightbox {
               # 'cssClass' and 'relAttribute' are not predefined in the constants
               # by default but in the Fluid Templates.
               cssClass = {$styles.content.textmedia.linkWrap.lightboxEnabled}
               relAttribute = {$styles.content.textmedia.linkWrap.lightboxCssClass}
           }
       }
   }
