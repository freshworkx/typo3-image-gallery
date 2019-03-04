[![Latest Stable Version](https://poser.pugx.org/bitmotion/bm-image-gallery/v/stable)](https://packagist.org/packages/bitmotion/bm-image-gallery)
[![Total Downloads](https://poser.pugx.org/bitmotion/bm-image-gallery/downloads)](https://packagist.org/packages/bitmotion/bm-image-gallery)
[![Latest Unstable Version](https://poser.pugx.org/bitmotion/bm-image-gallery/v/unstable)](https://packagist.org/packages/bitmotion/auth0)
[![Code Climate](https://codeclimate.com/github/bitmotion/typo3-image-gallery/badges/gpa.svg)](https://codeclimate.com/github/bitmotion/typo3-image-gallery)
[![License](https://poser.pugx.org/bitmotion/bm-image-gallery/license)](https://packagist.org/packages/bitmotion/bm-image-gallery)

# Simple Image Gallery (bm_image_gallery)

This extension creates galleries from images and Youtube videos 
organized within TYPO3's 'File collection' records. 

## For editors

The easiest way to get an front end output of your gallery is 
to follow these steps: 

1.  **Create one or more file collection records** in a folder 
inside your page tree and include your files. Each file collection 
represents a gallery.
2.  **Add a 'Simple Image Gallery' Plugin** on the page where you
want to show your gallery.

![Backend view of bm_image_gallery plugin](https://www.bitmotion.de/fileadmin/github/bm-image-gallery/bm_image_gallery-add-plugin.png "Add a 'Simple Image Gallery' plugin to a page")
3. **Choose the 'Display Mode'** in Plugin Options. There are two 
options: **'List'** will show a list multiple galleries (file 
collections) represented by the first file of the file collection 
in the frontend. A click on that file will show a gallery of all 
files in the file collection with click-enlarge functionality.

![Backend view of bm_image_gallery plugin](https://www.bitmotion.de/fileadmin/github/bm-image-gallery/bm_image_gallery-plugin-list.png "Backend view of bm_image_gallery plugin for a list")
**'Selected Gallery'** will show a single gallery with click-enlarge 
functionality. Thus you can add only a single reference to a file 
collection.

![Backend view of bm_image_gallery plugin](https://www.bitmotion.de/fileadmin/github/bm-image-gallery/bm_image_gallery-plugin-single.png "Backend view of bm_image_gallery plugin for a single File Collection")
4. **(Optional)** The Plugin Options provide a second tab named 
'Gallery'. Here you can limit the number of shown images the gallery. 
There are also options to sort images. 'Default' will take the order 
from the file collection.

![Backend view of bm_image_gallery plugin](https://www.bitmotion.de/fileadmin/github/bm-image-gallery/bm_image_gallery-plugin-sort-max.png "Backend view of bm_image_gallery plugin for limit number of images and sorting")

**Note: Avoid using multiple 'Simple Image Gallery' Plugins. This may 
lead to unintended output of the galleries.**

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

```
plugin.tx_bmimagegallery.view {
    templateRootPath = EXT:your_ext/Resources/Private/Template/Path/
    partialRootPath = EXT:your_ext/Resources/Private/Partial/Path/
    layoutRootPath = EXT:your_ext/Resources/Private/Layout/Path/
}
```

#### plugin.tx_bmimagegallery.settings
These settings may be overidden in your TypoScript:
```
*.overview.showCount = 1
```
Show the number of file collections. 1 means TRUE, 0 means FALSE.
```
*.overview.list.showCount = 1
```
Show the number of files in a gallery. 1 means TRUE, 0 means FALSE.
```
*.overview.list.showDescription = 1
```
Show a description of a file collection in gallery mode. 1 means 
TRUE, 0 means FALSE.
```
*.videos.youtube.params = autoplay=1&fs=1
```
Append params for YouTube videos.
```
*.videos.vimeo.params = color=000
```
Append params for Vimeo videos.
```
*.images.width = 300c
*.imagesmaxWidth = 500
*.imagesheight = 300c
*.imagesmaxHeight = 500
```
Here you can set image sizes.
```
*.lightbox.cssClass = {$styles.content.textmedia.linkWrap.lightboxEnabled}
*.lightbox.relAttribute = {$styles.content.textmedia.linkWrap.lightboxCssClass}
```
**'cssClass'** and **'relAttribute'** are not predefined in the
constants by default but in the Fluid Templates. 

### Templating

If you want to change the Fluid templates or use your own, copy the 
original files with the complete folder structure, e.g. into your 
site package and set the TypoScript configuration as shown above.
If TYPO3 finds a fluid template file under the given alternative path,
it will use this, otherwise the original files in the extension.