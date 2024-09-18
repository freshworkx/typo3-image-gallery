Simple Image Gallery for TYPO3
==============================

[![Latest Stable Version](https://poser.pugx.org/freshworkx/bm-image-gallery/v/stable)](https://packagist.org/packages/freshworkx/bm-image-gallery)
[![TYPO3 12](https://img.shields.io/badge/TYPO3-12-orange.svg)](https://get.typo3.org/version/12)
[![Build Status](https://github.com/freshworkx/typo3-image-gallery/workflows/Continuous%20Integration/badge.svg)](https://github.com/freshworkx/typo3-image-gallery)
[![License](https://poser.pugx.org/freshworkx/bm-image-gallery/license)](https://packagist.org/packages/freshworkx/bm-image-gallery)
[![Total Downloads](https://poser.pugx.org/freshworkx/bm-image-gallery/downloads)](https://packagist.org/packages/freshworkx/bm-image-gallery)

This extension creates galleries from images and Youtube videos organized within TYPO3's 'File collection' records.
The full documentation for the latest releases can be found [here](https://docs.typo3.org/p/freshworkx/bm-image-gallery/master/en-us/).

## Features

- List, detail and single view for File Collections
- Sorting, Limit of files 
- Extends TYPO3 'File Collections' by RTE field 'Gallery Description'
- TYPO3 9 or later: Route Enhancer example for speaking URLs, see  
the [example route enhancer](https://raw.githubusercontent.com/freshworkx/typo3-image-gallery/master/Resources/Private/Examples/RouteEnhancer.yml "Example route enhancer for gallery detail pages")

## For editors

The 'Simple Image Gallery' Plugin provides various options to cover different scenarios. 

### Quick & easy
The easiest way to get an front end output of your gallery is to follow these steps (default scenario): 

1. **Create one or more file collection records** in a folder inside your page tree and include your files. Each file collection 
represents a gallery.

2. **Add a 'Simple Image Gallery' Plugin** on the page where you want to show your gallery.
![Backend view of bm_image_gallery plugin](https://raw.githubusercontent.com/freshworkx/typo3-image-gallery/master/Documentation/Editor/Images/add-plugin.png "Add a 'Simple Image Gallery' plugin to a page")

3. You have to **select the plugin type** that fits your needs.
![Select the proper EXT:bm_image_gallery plugin](https://raw.githubusercontent.com/freshworkx/typo3-image-gallery/master/Documentation/Editor/Images/select-plugin.png "Select the proper EXT:bm_image_gallery plugin")

4. **Add references to your file collections** in the 'File Collection' section.
![Backend view of EXT:bm_image_gallery plugin for a gallery list](https://raw.githubusercontent.com/freshworkx/typo3-image-gallery/master/Documentation/Editor/Images/plugin.png "Backend view of bm_image_gallery plugin for a list")
 
4. **(Optional)** The Plugin Options provide a second tab named 'Gallery'. Here you can limit the number of shown images the
gallery. There are also options to sort images. 'Default' will take the order from the file collection.
![Backend view of EXT:bm_image_gallery plugin for limiting number of images and sorting](https://raw.githubusercontent.com/freshworkx/typo3-image-gallery/master/Documentation/Editor/Images/plugin-sort-max.png "Backend view of bm_image_gallery plugin for limit number of images and sorting")

### Possible scenarios

There are the following scenarios to display galleries:

#### Scenario 1: A list of galleries with gallery view on the same page as the plugin (default). 

For that choose the "Image Gallery: Gallery List" plugin and select "Same Page" as "Detail View" option.

---
#### Scenario 2: A list of galleries with gallery view on another page as the plugin. 

For that choose the "Image Gallery: Gallery List" plugin and select "Selected Page" as "Detail View" option. After an automatic
reload, you will find an additional configuration option "Gallery Page" at the bottom of the screen. Please add a reference to the
target detail page here. Save the plugin and navigate to the page just selected.
Create a new plugin of type "Image Gallery: Elements of Gallery". Now you are all set.

This scenario is recommended for multiple plugins on the same page.

---
#### Scenario 3: A list of galleries without gallery view. 

For that choose the "Image Gallery: Gallery List" plugin and select "No Detail View" as "Detail View" option.

---
#### Scenario 4: A gallery view for a single gallery. 

For that choose the "Image Gallery: Selected Gallery" plugin.
