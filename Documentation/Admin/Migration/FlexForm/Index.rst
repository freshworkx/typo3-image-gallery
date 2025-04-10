.. include:: ../../../Includes.txt

.. _flexform:

========================================
Migration `flexForm` settings of plugins
========================================

.. important::

   Please make sure that you have successfully completed the :ref:`_listtype` wizard **first**!

The TCA of `file_collections` and the settings in `flexForm` of plugins had to be adjusted.

For this migration the extension provide an upgrade wizard which migrates all `bm_image_gallery` plugins.
The upgrade wizard is available in the Backend or via CLI:

.. code-block:: bash
    :caption: CLI example to run migration wizard

   ./bin/typo3 upgrade:run bmImageGalleryFlexFormMigration