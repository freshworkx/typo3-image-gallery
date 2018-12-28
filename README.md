# Simple Image Gallery (bm_image_gallery)

This extension creates galleries from images and Youtube videos 
organized within TYPO3's 'File collection' records. 

## For editors

The easiest way to get an front end output of your gallery is 
to follow these steps: 

1.  **Create one or more file collection records** in a folder inside 
your page tree and include your files. Each file collection 
represents a gallery.
2.  **Create a general plugin** on the page where you want to show your 
gallery. Then select the plugin "Gallery" on the "Plugin" tab.
3.  **Create references to your file collections** in the multiselect
field below the "Plugin Options" section.

Assuming you referenced only one file storage, all the files of this 
file storage should be shown in a **list view** with click-enlarge 
functionality. 

If you have referenced **multiple file collections** inside the plugin, 
an **overview** of all galleries will be rendered in the front end. Click 
one of them to get a **list view** of all files of the gallery.

In case of multiple file collections you can also define a **"Gallery 
Page"** optionally. On that page create another gallery plugin with the
**same file storages referenced** as the originally plugin. If so, the 
gallery overview links to the "Gallery Page", where the list view
will be rendered. This can be necessary, if there are multiple plugins
with multiple galleries on one page.

![Backend view of bm_image_gallery plugin](https://www.bitmotion.de/fileadmin/github/bm-image-gallery/bm_image_gallery-plugin.png "Backend view of bm_image_gallery plugin")

## For administrators

### Installation

If you are in composer mode, use

    composer req bitmotion/bm-image-gallery

The extension is available in the [TYPO3 Extension Repository (TER)](https://extensions.typo3.org/extension/bm_image_gallery/ "bm_image_gallery in TER") 
and can also be installed via Extension Manager. Ensure, that the static 
templates are included.

### Configuration

Set alternative Layout/Template/Partial path individually to use your own 
Fluid templates. Simply set the following TypoScript constants:

```
plugin.tx_bmimagegallery.view {
    templateRootPath = EXT:your_ext/Resources/Private/Template/Path/
    partialRootPath = EXT:your_ext/Resources/Private/Partial/Path/
    layoutRootPath = EXT:your_ext/Resources/Private/Layout/Path/
}
```

### Templating

If you want to change the Fluid templates or use your own, copy the 
original files with the complete folder structure, e.g. into your 
site package and set the TypoScript configuration as shown above.
If TYPO3 finds a fluid template file under the given alternative path,
it will use this, otherwise the original files in the extension.