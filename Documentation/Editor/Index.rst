.. include:: ../Includes.txt

.. _editor:

===========
For Editors
===========

The `Image Gallery` plugins provide various options to cover different scenarios.

.. _editor-quickAndEasy:

Quick & Easy
============

The easiest way to get an front end output of your gallery is to follow these steps (default scenario):

.. rst-class:: bignums-xxl

1. Create file collection(s)

   Create one or more file collection records in a folder inside your page tree and include your files. Each file collection represents a gallery.
   The `bm_image_gallery` extension extends TYPO3's file collection record. You can find some extra fields under the tab `Image Gallery`.

   **Optionally**, you have the possibility to enter various details about your collection here.

   .. figure:: Images/file-collection.png
       :class: with-shadow
       :alt: Backend view of a file collection record

   .. note::
       If your file collection do not contain

       * a gallery description, the description of the file collection itself is used as fallback.
       * a preview image, the first image of the file collection is taken as fallback (old behavior).

2. Select the proper plugin

   .. figure:: Images/add-plugin.png
      :class: with-shadow
      :alt: Select the proper EXT:bm_image_gallery plugin

   Backend view of the `new content element` wizard. Add a `Plugin` content element on the page where you want to show your gallery.
   You have to choose the proper plugin type that fits your requirements.

3. Add reference

   Add references to your file collections in the `File Collection` section.

   .. figure:: Images/plugin.png
      :class: with-shadow
      :alt: Backend view of EXT:bm_image_gallery plugin for a gallery list

   Backend view of plugin for a gallery list.


4. Plugin options

   The Plugin options provide a second section where you can limit the number of shown images the gallery. There are
   also options to sort images. `Default` will take the order from the file collection.

   .. figure:: Images/plugin-sort-max.png
      :class: with-shadow
      :alt: Backend view of EXT:bm_image_gallery plugin for limiting number of images and sorting

   Backend view of plugin options for limiting number of images and sorting.

.. _editor-possibleScenarios:

Possible Scenarios
==================

There are the following scenarios to display galleries:

* **List of Galleries with Gallery View on Same Page**

  For that choose the `Gallery List` plugin and select `Same Page` as `Detail View` option.

* **List of Galleries with Gallery View on a Different Page**

    This scenario is recommended for multiple plugins on the same page.

  For that choose the `Gallery List` plugin and select `Selected Page` as `Detail View` option. After an automatic
  reload, you will find an additional configuration option `Gallery Page` at the bottom of the screen. Please add a reference to the
  target detail page here. Save the plugin and navigate to the page just selected.
  Create a new plugin of type `Elements of Gallery`. Now you are all set.

* **List of Galleries without Gallery View**

  For that choose the `Gallery List` plugin and select `No Detail View` as `Detail View` option.

* **Gallery View for a Single Gallery**

  For that choose the `Selected Gallery` plugin.
