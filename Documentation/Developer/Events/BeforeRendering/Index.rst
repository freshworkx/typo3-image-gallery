.. include:: ../../../Includes.txt

.. _beforeRendering:

====================
BeforeRenderingEvent
====================

Example Use Cases
=================

.. contents::
   :local:
   :depth: 2

.. _beforeRendering-analytics:

Add Analytics Tracking
-----------------------

This example shows how to add analytics tracking data to view variables.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\BeforeRenderingEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/add-analytics',
       event: BeforeRenderingEvent::class
   )]
   final readonly class AddAnalyticsListener
   {
       public function __invoke(BeforeRenderingEvent $event): void
       {
           // Add tracking
           $event->setViewVariable('trackingAction', 'gallery_' . $event->getAction());

           // Add page context
           $pageId = $event->getRequest()->getAttribute('routing')?->getPageId();
           $event->setViewVariable('pageId', $pageId);
       }
   }

.. _beforeRendering-variables:

Add Custom View Variables
--------------------------

This example demonstrates adding custom variables for use in templates.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\BeforeRenderingEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/add-custom-variables',
       event: BeforeRenderingEvent::class
   )]
   final readonly class AddCustomVariablesListener
   {
       public function __invoke(BeforeRenderingEvent $event): void
       {
           // Add custom variables
           $event->setViewVariable('analyticsId', 'UA-12345-6');
           $event->setViewVariable('customData', ['foo' => 'bar']);
       }
   }

.. _beforeRendering-modify:

Modify Existing Variables
--------------------------

This example shows how to modify existing view variables before rendering.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\BeforeRenderingEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/modify-variables',
       event: BeforeRenderingEvent::class
   )]
   final readonly class ModifyVariablesListener
   {
       public function __invoke(BeforeRenderingEvent $event): void
       {
           $vars = $event->getViewVariables();

           // Modify collection title
           if (isset($vars['fileCollection']['title'])) {
               $vars['fileCollection']['title'] = 'Modified: ' . $vars['fileCollection']['title'];
           }

           $event->setViewVariables($vars);
       }
   }

.. _beforeRendering-action:

Action-Specific Logic
----------------------

This example demonstrates action-specific modifications.

.. code-block:: php

   <?php

   declare(strict_types=1);

   namespace MyVendor\MyExtension\EventListener;

   use Freshworkx\BmImageGallery\Event\BeforeRenderingEvent;
   use TYPO3\CMS\Core\Attribute\AsEventListener;

   #[AsEventListener(
       identifier: 'my-ext/action-specific',
       event: BeforeRenderingEvent::class
   )]
   final readonly class ActionSpecificListener
   {
       public function __invoke(BeforeRenderingEvent $event): void
       {
           // Different logic based on action
           if ($event->getAction() === 'detail') {
               $event->setViewVariable('showBreadcrumb', true);
               $event->setViewVariable('showBackButton', true);
           }

           if ($event->getAction() === 'list') {
               $event->setViewVariable('showFilters', true);
           }

           if ($event->getAction() === 'gallery') {
               $event->setViewVariable('showLightbox', true);
           }
       }
   }

