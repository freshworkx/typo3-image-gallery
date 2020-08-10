.. include:: ../Includes.txt

.. _editor:

===========
For Editors
===========

The 'Simple Image Gallery' plugins provide various options to cover different scenarios.

.. _editor-quickAndEasy:

Quick & Easy
============

The easiest way to get an front end output of your gallery is to follow these steps (default scenario):

.. rst-class:: bignums-xxl

1. Create file collection(s)

   Create one or more file collection records in a folder inside your page tree and include your files. Each file collection
   represents a gallery.

2. Add plug in

   Add a 'General Plugin' content elmement on the page where you want to show your gallery.

   .. figure:: Images/add-plugin.png
      :class: with-shadow
      :alt: Backend view of EXT:bm_image_gallery plugin

   Backend view of the "new content element" wizard.

3. Select the proper plugin

   .. figure:: Images/select-plugin.png
      :class: with-shadow
      :alt: Select the proper EXT:bm_image_gallery plugin

   You have to choose the proper plugin type that fits your.

4. Add reference

   Add references to your file collections in the 'File Collection' section.

   .. figure:: Images/plugin.png
      :class: with-shadow
      :alt: Backend view of EXT:bm_image_gallery plugin for a gallery list

   Backend view of plugin for a gallery list.


4. Limit amount of shown images (optional)

   The Plugin Options provide a second tab named 'Gallery'. Here you can limit the number of shown images the gallery. There are
   also options to sort images. 'Default' will take the order from the file collection.

   .. figure:: Images/plugin-sort-max.png
      :class: with-shadow
      :alt: Backend view of EXT:bm_image_gallery plugin for limiting number of images and sorting

   Backend view of plugin for limiting number of images and sorting.

.. _editor-possibleScenarios:

Possible Scenarios
==================

There are the following scenarios to display galleries:

List of Galleries w/ Gallery View on Same Page
----------------------------------------------

For that choose the "Image Gallery: Gallery List" plugin and select "Same Page" as "Detail View" option.

List of Galleries w/ Gallery View on a Different Page
-----------------------------------------------------

.. note::

   This scenario is recommended for multiple plugins on the same page.

For that choose the "Image Gallery: Gallery List" plugin and select "Selected Page" as "Detail View" option. After an automatic
reload, you will find an additional configuration option "Gallery Page" at the bottom of the screen. Please add a reference to the
target detail page here. Save the plugin and navigate to the page just selected.
Create a new plugin of type "Image Gallery: Elements of Gallery". Now you are all set.

List of Galleries w/o Gallery View
------------------------------------

For that choose the "Image Gallery: Gallery List" plugin and select "No Detail View" as "Detail View" option.

Gallery View for a Single Gallery
-----------------------------------

For that choose the "Image Gallery: Selected Gallery" plugin.
