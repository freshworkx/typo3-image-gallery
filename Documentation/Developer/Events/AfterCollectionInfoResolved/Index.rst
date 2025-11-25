.. include:: ../../../Includes.txt

.. _afterCollectionInfoResolved:

================================
AfterCollectionInfoResolvedEvent
================================

Example Use Cases
=================

.. contents::
   :local:
   :depth: 2

.. _afterCollectionInfoResolved-metadata:

Add Custom Metadata
--------------------

This example shows how to add formatted dates and SEO metadata to collections.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterCollectionInfoResolvedEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/add-metadata',
       event: AfterCollectionInfoResolvedEvent::class
   )]
   final readonly class AddMetadataListener
   {
       public function __invoke(AfterCollectionInfoResolvedEvent $event): void
       {
           $info = $event->getCollectionInfo();

           // Add formatted date
           if ($info['date']) {
               $info['formattedDate'] = date('d.m.Y', $info['date']);
           }

           // Add SEO data
           $info['metaDescription'] = sprintf(
               'Gallery "%s" with %d images',
               $info['title'],
               $info['itemCount']
           );

           $event->setCollectionInfo($info);
       }
   }

.. _afterCollectionInfoResolved-fields:

Add Custom Fields
-----------------

This example demonstrates adding custom fields to the collection info array.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterCollectionInfoResolvedEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/add-custom-fields',
       event: AfterCollectionInfoResolvedEvent::class
   )]
   final readonly class AddCustomFieldsListener
   {
       public function __invoke(AfterCollectionInfoResolvedEvent $event): void
       {
           $info = $event->getCollectionInfo();

           // Add custom fields
           $info['formattedDate'] = date('d.m.Y', $info['date']);
           $info['hasMultipleImages'] = $info['itemCount'] > 1;

           $event->setCollectionInfo($info);
       }
   }

.. _afterCollectionInfoResolved-modify:

Modify Existing Fields
-----------------------

This example shows how to modify existing collection fields.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterCollectionInfoResolvedEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/modify-title',
       event: AfterCollectionInfoResolvedEvent::class
   )]
   final readonly class ModifyTitleListener
   {
       public function __invoke(AfterCollectionInfoResolvedEvent $event): void
       {
           $info = $event->getCollectionInfo();

           // Modify title
           $info['title'] = '[Gallery] ' . $info['title'];

           $event->setCollectionInfo($info);
       }
   }

.. _afterCollectionInfoResolved-external:

Add External Data
-----------------

This example demonstrates enriching collections with external data.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\AfterCollectionInfoResolvedEvent;
   use MyVendor\MyExtension\Service\StatisticsService;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/add-statistics',
       event: AfterCollectionInfoResolvedEvent::class
   )]
   final readonly class AddStatisticsListener
   {
       public function __construct(
           private StatisticsService $statisticsService
       ) {}

       public function __invoke(AfterCollectionInfoResolvedEvent $event): void
       {
           $info = $event->getCollectionInfo();

           // Add external data
           $info['downloadCount'] = $this->statisticsService
               ->getDownloadCount($info['identifier']);
           $info['viewCount'] = $this->statisticsService
               ->getViewCount($info['identifier']);

           $event->setCollectionInfo($info);
       }
   }
