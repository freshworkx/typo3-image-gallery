.. include:: ../../Includes.txt

.. _templates:

=========
Templates
=========

Set alternative Layout/Template/Partial path individually to use your own Fluid templates. Simply set the following TypoScript
constants:

.. code-block:: typoscript

   plugin.tx_bmimagegallery.view {
       templateRootPath = EXT:your_ext/Resources/Private/Template/Path/
       partialRootPath = EXT:your_ext/Resources/Private/Partial/Path/
       layoutRootPath = EXT:your_ext/Resources/Private/Layout/Path/
   }
