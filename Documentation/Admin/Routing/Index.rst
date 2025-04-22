.. include:: ../../Includes.txt

.. _routing:

==============================
Advanced routing configuration
==============================

Since TYPO3 v9 introduced native routing to the Core, it is possible to define route enhancers of various types.
These route enhancers transform ugly URL's into nice readable.

An example of the route enhancer definition for the `Gallery List` plugin is stored in the extension itself.

.. code-block:: yaml
    :caption: bm_image_gallery/Configuration/Routes/List.yaml

    routeEnhancers:
      GalleryPlugin:
        aspects:
          gallery_name:
            type: PersistedAliasMapper
            tableName: sys_file_collection
            routeFieldName: bm_image_gallery_path_segment
          localized_gallery:
            type: LocaleModifier
            default: 'gallery'
            localeMap:
              -
                locale: 'de_*'
                value: 'galerie'
        defaultController: 'Gallery::list'
        extension: BmImageGallery
        plugin: GalleryList
        routes:
          -
            routePath: '/{localized_gallery}/{gallery_name}'
            _controller: 'Gallery::detail'
            _arguments:
              gallery_name: show
        type: Extbase

You can import the file **optionally** into your own website configuration:

.. code-block:: yaml
    :caption: config/sites/<some_site>/config.yaml

    # ... some more configuration is here ...
    imports:
      - { resource: "EXT:bm_image_gallery/Configuration/Routes/List.yaml" }

.. note::
    Feel free to import or overwrite your own route enhancer definition in the website configuration.