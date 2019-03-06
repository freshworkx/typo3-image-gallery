[![Latest Stable Version](https://poser.pugx.org/bitmotion/bm-image-gallery/v/stable)](https://packagist.org/packages/bitmotion/bm-image-gallery)
[![Total Downloads](https://poser.pugx.org/bitmotion/bm-image-gallery/downloads)](https://packagist.org/packages/bitmotion/bm-image-gallery)
[![Latest Unstable Version](https://poser.pugx.org/bitmotion/bm-image-gallery/v/unstable)](https://packagist.org/packages/bitmotion/auth0)
[![Code Climate](https://codeclimate.com/github/bitmotion/typo3-image-gallery/badges/gpa.svg)](https://codeclimate.com/github/bitmotion/typo3-image-gallery)
[![License](https://poser.pugx.org/bitmotion/bm-image-gallery/license)](https://packagist.org/packages/bitmotion/bm-image-gallery)

# Simple Image Gallery (bm_image_gallery)

This extension creates galleries from images and Youtube videos 
organized within TYPO3's 'File collection' records.

## Features

- List, detail and single view for File Collections
- Sorting, Limit of files 
- Extends TYPO3 'File Collections' by RTE field 'Gallery Description'
- TYPO3 9: Route Enhancer example for speaking URLs, see  
EXT:bm_image_gallery/Resources/Private/Examples/RouteEnhancer.yml

## For editors

The 'Simple Image Gallery' Plugin provides various options to 
cover different scenarios. 

### Quick & easy
The easiest way to get an front end output of your gallery is 
to follow these steps (default scenario): 

1. **Create one or more file collection records** in a folder 
inside your page tree and include your files. Each file collection 
represents a gallery.
2. **Add a 'Simple Image Gallery' Plugin** on the page where you
want to show your gallery.
![Backend view of bm_image_gallery plugin](https://www.bitmotion.de/fileadmin/github/bm-image-gallery/bm_image_gallery-add-plugin.png "Add a 'Simple Image Gallery' plugin to a page")

3. **Add references to your file collections** in the 'File Collection' 
section.
![Backend view of bm_image_gallery plugin](https://www.bitmotion.de/fileadmin/github/bm-image-gallery/bm_image_gallery-plugin.png "Backend view of bm_image_gallery plugin for a list")
 
4. **(Optional)** The Plugin Options provide a second tab named 
'Gallery'. Here you can limit the number of shown images the gallery. 
There are also options to sort images. 'Default' will take the order 
from the file collection.
![Backend view of bm_image_gallery plugin](https://www.bitmotion.de/fileadmin/github/bm-image-gallery/bm_image_gallery-plugin-sort-max.png "Backend view of bm_image_gallery plugin for limit number of images and sorting")


### Possible scenarios

There are the following scenarios to display galleries: 

#### Scenario 1: A list of galleries with gallery view on the same page as the plugin (default). 
For that set:      
- **'Display Mode'** -> **'List'**  
- **'Detail View'** -> **'Same Page'**
---
#### Scenario 2: A list of galleries with gallery view on another page as the plugin. 
Recommended for multiple Plugins on the same page.  
For that set:   
- **'Display Mode'** -> **'List'**  
- **'Detail View'** -> **'Selected Page'**  
- **Create a reference** to another page (target page).  
- **Create a 'Simple Image Gallery' Plugin** on the target page.  
- Inside the plugin on the target page set **'Display Mode'** -> **'Gallery View'**
---
#### Scenario 3: A list of galleries without gallery view. 
For that set:  
- **'Display Mode'** -> **'List'** 
- **'Detail View'** -> **'No Detail View'**
---
#### Scenario 4: A gallery view for a single gallery. 
For that set:  
- **'Display Mode'** -> **'Selected Gallery'** 


## For administrators

### Installation

If you are in composer mode, use

    composer req bitmotion/bm-image-gallery

The extension is available in the [TYPO3 Extension Repository (TER)](https://extensions.typo3.org/extension/bm_image_gallery/ "bm_image_gallery in TER") 
and can also be installed via Extension Manager. Ensure, that the 
static templates are included.

### Configuration

#### Templates
Set alternative Layout/Template/Partial path individually to use 
your own Fluid templates. Simply set the following TypoScript 
constants:

	plugin.tx_bmimagegallery.view {
		templateRootPath = EXT:your_ext/Resources/Private/Template/Path/
		partialRootPath = EXT:your_ext/Resources/Private/Partial/Path/
		layoutRootPath = EXT:your_ext/Resources/Private/Layout/Path/
	}

#### Settings
These settings may be overwritten in your TypoScript:

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

### Templating

If you want to change the Fluid templates or use your own, copy the 
original files with the complete folder structure, e.g. into your 
site package and set the TypoScript configuration as shown above.
If TYPO3 finds a fluid template file under the given alternative path,
it will use this, otherwise the original files in the extension.