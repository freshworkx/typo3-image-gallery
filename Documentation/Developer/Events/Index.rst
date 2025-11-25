.. include:: ../../Includes.txt

.. _events:

======
Events
======

The BM Image Gallery extension dispatches events that allow you to hook into the gallery rendering process at strategic
points. These events follow TYPO3's PSR-14 event system and can be used to:

* Modify collection data
* Filter or enrich items
* Add template variables
* Implement custom business logic

.. _events-overview:

Event Overview
--------------

.. csv-table:: Available Events
   :header: "Event", "When Dispatched", "Main Purpose"
   :widths: 30, 30, 25

   "AfterCollectionInfoResolvedEvent", "After complete processing", "Enrich collection info array"
   "AfterItemsSortedEvent", "After sort + limit", "Post-process sorted items"
   "BeforeRenderingEvent", "Before view assignment", "Add template variables"

.. _events-aftercollectioninfo:

AfterCollectionInfoResolvedEvent
---------------------------------

**Class:** :php:`Freshworkx\BmImageGallery\Event\AfterCollectionInfoResolvedEvent`

**When is it dispatched?**

* After all extension processing
* After description/preview fallbacks
* After items have been sorted (if requested)

.. seealso::
   For implementation examples, see :ref:`afterCollectionInfoResolved`.

.. _events-afteritemssorted:

AfterItemsSortedEvent
---------------------

**Class:** :php:`Freshworkx\BmImageGallery\Event\AfterItemsSortedEvent`

**When is it dispatched?**

* After ``FileCollector->sort()``
* After ``maxItems`` limit

.. seealso::
   For implementation examples, see :ref:`afterItemsSorted`.

.. _events-beforerendering:

BeforeRenderingEvent
--------------------

**Class:** :php:`Freshworkx\BmImageGallery\Event\BeforeRenderingEvent`

**When is it dispatched?**

* Dispatched in **all actions** (list, gallery, detail)
* Before :php:`$this->view->assign()` or :php:`assignMultiple()`

.. seealso::
   For implementation examples, see :ref:`beforeRendering`.

.. _events-registration:

Event Registration
------------------

Method 1: PHP Attribute (Recommended)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterCollectionInfoResolvedEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/my-listener',
       event: AfterCollectionInfoResolvedEvent::class
   )]
   final readonly class MyListener
   {
       public function __invoke(AfterCollectionInfoResolvedEvent $event): void
       {
           // Your logic
       }
   }

Ensure autoconfiguration in :file:`Configuration/Services.yaml`:

.. code-block:: yaml

   services:
     _defaults:
       autowire: true
       autoconfigure: true
       public: false

     MyVendor\MyExtension\EventListener\:
       resource: '../Classes/EventListener/*'

Method 2: Services.yaml
^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: yaml

   services:
     MyVendor\MyExtension\EventListener\MyListener:
       tags:
         - name: event.listener
           identifier: 'my-ext/my-listener'
           event: Freshworkx\BmImageGallery\Event\AfterCollectionInfoResolvedEvent

.. _events-resources:

Further Resources
=================

* `TYPO3 PSR-14 Documentation <https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Events/EventDispatcher/Index.html>`__

.. toctree::
   :maxdepth: 2
   :hidden:

   AfterCollectionInfoResolved/Index
   AfterItemsSorted/Index
   BeforeRendering/Index
