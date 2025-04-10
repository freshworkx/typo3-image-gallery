.. include:: ../../Includes.txt

.. _configuration:

=============
Configuration
=============

The following configuration may be overwritten in your TypoScript constants.

.. _configuration-list:

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

.. _configuration-gallery:

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

.. _configuration-videos:

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

.. _configuration-images:

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

.. _configuration-lightbox:

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
