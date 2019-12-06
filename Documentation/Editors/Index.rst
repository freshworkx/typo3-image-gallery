.. include:: ../Includes.txt

.. _editor:

===========
For Editors
===========

The 'Simple Image Gallery' Plugin provides various options to cover different scenarios.

Quick & Easy
============

The easiest way to get an front end output of your gallery is to follow these steps (default scenario):

.. rst-class:: bignums-xxl

1. Create file collection(s)

   Create one or more file collection records in a folder inside your page tree and include your files. Each file collection
   represents a gallery.

2. Add plug in

   Add a 'Simple Image Gallery' Plugin on the page where you want to show your gallery.

3. Add reference

   Add references to your file collections in the 'File Collection' section.

4. Limit amount of shown images (optional)

   The Plugin Options provide a second tab named 'Gallery'. Here you can limit the number of shown images the gallery. There are
   also options to sort images. 'Default' will take the order from the file collection.

Possible Scenarios
==================

There are the following scenarios to display galleries:

List of Galleries w/ Gallery View on Same Page
----------------------------------------------

For that set:

* **'Display Mode'** -> **'List'**
* **'Detail View'** -> **'Same Page'**

List of Galleries w/ Gallery View on a Different Page
-----------------------------------------------------

Recommended for multiple plug ins on the same page.
For that set:

* **'Display Mode'** -> **'List'**
* **'Detail View'** -> **'Selected Page'**
* **Create a reference** to another page (target page).
* **Create a 'Simple Image Gallery' Plugin** on the target page.
* Inside the plugin on the target page set **'Display Mode' -> 'Gallery View'**

List of Galleries w/o Gallery View
------------------------------------

For that set:

* **'Display Mode'** -> **'List'**
* **'Detail View'** -> **'No Detail View'**

Gallery View for a Single Gallery
-----------------------------------

For that set:

* **'Display Mode'** -> **'Selected Gallery'**
