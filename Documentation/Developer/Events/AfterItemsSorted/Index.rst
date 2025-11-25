.. include:: ../../../Includes.txt

.. _afterItemsSorted:

=====================
AfterItemsSortedEvent
=====================

Example Use Cases
=================

.. contents::
   :local:
   :depth: 2

.. _afterItemsSorted-filter:

Filter Items by Extension
--------------------------

This example shows how to filter items to only allow specific file types.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterItemsSortedEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/filter-items',
       event: AfterItemsSortedEvent::class
   )]
   final readonly class FilterItemsListener
   {
       public function __invoke(AfterItemsSortedEvent $event): void
       {
           $items = $event->getItems();

           // Only allow JPG and PNG
           $filtered = array_filter($items, function($item) {
               return in_array(
                   strtolower($item->getExtension()),
                   ['jpg', 'jpeg', 'png'],
                   true
               );
           });

           $event->setItems(array_values($filtered));
       }
   }

.. _afterItemsSorted-size:

Filter Items by Size
--------------------

This example demonstrates filtering items based on file size.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterItemsSortedEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/filter-by-size',
       event: AfterItemsSortedEvent::class
   )]
   final readonly class FilterBySizeListener
   {
       public function __invoke(AfterItemsSortedEvent $event): void
       {
           $items = $event->getItems();

           // Only allow files larger than 1MB
           $filtered = array_filter($items, fn($item) => $item->getSize() > 1048576);

           $event->setItems(array_values($filtered));
       }
   }

.. _afterItemsSorted-enrich:

Enrich Items
------------

This example shows how to enrich items with additional metadata.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterItemsSortedEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;
   use TYPO3\CMS\Core\Resource\ProcessedFile;

   #[AsEventListener(
       identifier: 'my-ext/enrich-items',
       event: AfterItemsSortedEvent::class
   )]
   final readonly class EnrichItemsListener
   {
       public function __invoke(AfterItemsSortedEvent $event): void
       {
           $items = $event->getItems();

           foreach ($items as $item) {
               // Add metadata or prepare thumbnails
               // This is just for demonstration - in real scenarios,
               // you might want to add custom properties or process files
           }

           $event->setItems($items);
       }
   }

.. _afterItemsSorted-resort:

Re-sort Items
-------------

This example demonstrates custom sorting of items.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterItemsSortedEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/resort-items',
       event: AfterItemsSortedEvent::class
   )]
   final readonly class ResortItemsListener
   {
       public function __invoke(AfterItemsSortedEvent $event): void
       {
           $items = $event->getItems();

           // Sort alphabetically by name
           usort($items, fn($a, $b) => $a->getName() <=> $b->getName());

           $event->setItems($items);
       }
   }


