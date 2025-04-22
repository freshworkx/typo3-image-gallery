.. include:: ../../../Includes.txt

.. _listtype:

================================
Migration `list_type` to `CType`
=================================

.. important::

   Please make sure that you have successfully completed this wizard first!

According to the TYPO3 deprecation `#105076<https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105076-PluginContentElementAndPluginSubTypes.html#deprecation-105076-plugin-content-element-and-plugin-sub-types>`__.
the plugin content element `list` and the plugin sub types field `list_type` have been marked as deprecated.

For this migration the extension provide an upgrade wizard which migrates all `bm_image_gallery` plugins from `list_type` to `CType` definition.
The upgrade wizard is available in the Backend or via CLI:

.. code-block:: bash
    :caption: CLI example to run migration wizard

   ./bin/typo3 upgrade:run bmImageGalleryCTypeMigration
