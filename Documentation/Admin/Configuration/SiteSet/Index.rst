.. include:: ../../../Includes.txt

.. _site-set:

========
Site Set
========

This site set provides modern TYPO3 v13 configuration for the `bm_image_gallery` extension using site settings.

.. note::
   More information about TYPO3 Site Sets can be found `here<https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/SiteHandling/SiteSets.html>`__.


.. _site-set-installation:

Installation
============

Add the site set to your site configuration in the site management module or directly in your site's :file:`config.yaml`:

.. code-block:: yaml

   dependencies:
     - freshworkx/bm-image-gallery

.. _site-set-configuration:

Configuration
=============

You can configure the `bm_image_gallery` settings in three ways:

Option A: Through the Backend
-----------------------------

1. Go to **Site Management > Sites**
2. Edit your site
3. Navigate to the **Settings** tab
4. Find the **Plugin Configuration > Image Gallery** section
5. Configure your settings

Option B: In Site Configuration YAML
------------------------------------

Add to your site's :file:`config/sites/<your-site>/settings.yaml`:

.. code-block:: yaml

   plugin:
    tx_bmimagegallery:
      persistence:
        storagePid: 123
      settings:
        gallery:
          showCount: true
          showDescription: true
        images:
          maxWidth: 800
          maxHeight: 600
        pagination:
          itemsPerPage: 20

.. versionchanged:: 13.4.15
   The settings in :file:`settings.yaml` are stored as map instead of tree.

   `Important: #106894 - Site settings.yaml is now stored as a map <https://docs.typo3.org/permalink/changelog:important-106894-1750144877>`_

Option C: Override in Site Set
------------------------------

Create a custom site set that depends on this one and override settings in your :file:`settings.yaml`.

.. _site-set-migration:

Migration from TypoScript Constants
====================================

No Migration Needed!
--------------------

Simply enable the site set - everything continues to work! Your existing constant overrides will continue to function until you move them to Site Settings.

Site Settings use **exactly the same paths** as TypoScript constants:

.. code-block:: typoscript

   # This TypoScript works with both constants AND Site Settings:
   plugin.tx_bmimagegallery.settings.gallery.showCount = {$plugin.tx_bmimagegallery.settings.gallery.showCount}

.. important::
   **When you set a site setting, it automatically takes priority over the constant!**

.. _site-set-optional-migration:

Optional: Move to Site Settings
--------------------------------

If you want to use the Backend UI or per-site configuration:

1. Enable the site set in your site configuration
2. Test that everything works as before
3. Move your custom constant values to site settings
4. Remove the constant overrides when ready

.. _site-set-typoscript-access:

TypoScript Access
=================

Settings are available using the standard constant syntax:

.. code-block:: typoscript

   plugin.tx_bmimagegallery.settings.gallery.showCount = {$plugin.tx_bmimagegallery.settings.gallery.showCount}

.. note::
   No changes needed! Site Settings use the same paths as constants.

.. _site-set-fluid-access:

Fluid Access
============

Access settings in Fluid templates:

.. code-block:: html

   <!-- Same as constants, just via site settings -->
   {site.settings.plugin.tx_bmimagegallery.settings.gallery.showCount}

.. _site-set-php-access:

PHP Access
==========

Access settings in PHP using the SiteSettings API:

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace YourVendor\YourExtension\Controller;

   use TYPO3\CMS\Core\Site\Entity\SiteInterface;

   final class YourController
   {
       public function __construct(
           private readonly SiteInterface $site
       ) {}

       public function getSetting(): int
       {
           return (int)$this->site->getSettings()
               ->get('plugin.tx_bmimagegallery.settings.pagination.itemsPerPage', 10);
       }
   }
