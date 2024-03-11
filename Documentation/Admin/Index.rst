.. include:: ../Includes.txt

.. _admin:

==================
For Administrators
==================

.. _admin-installation:

Installation
============

There are several ways to require and install this extension. We recommend to get this extension via
`composer <https://getcomposer.org/>`__.

.. _admin-installation-composer:

Via Composer
------------

If your TYPO3 instance is running in composer mode, you can simply require the extension by running:

.. code-block:: bash

   composer req freshworkx/bm-image-gallery

.. _admin-installation-extensionManager:

Via Extension Manager
---------------------

Open the extension manager module of your TYPO3 instance and select "Get Extensions" in the select menu above the upload
button. There you can search for `bm_image_gallery` and simply install the extension. Please make sure you are using the latest
version of the extension by updating the extension list before installing the Auth0 extension.

.. _admin-installation-zipFile:

Via ZIP File
------------

You need to download the Auth0 extension from the `TYPO3 Extension Repository <https://extensions.typo3.org/extension/bm_image_gallery/>`__
and upload the zip file to the extension manager of your TYPO3 instance and activate the extension afterwards.

.. important::

   Please make sure to include all TypoScript files.

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

You can use your own lightbox library for showing images and videos in a lightbox. Several information are available in the anchor
tag which should trigger the lightbox. However, you can add your own CSS class and rel attribute to adapt the source code to
your needs.

.. code-block:: typoscript

   plugin.tx_bmimagegallery {
       settings {
           lightbox {
               cssClass =
               relAttribute =
           }
       }
   }
