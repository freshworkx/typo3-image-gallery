<?php

declare(strict_types=1);

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Jens Neumann <info@freshworkx.de>
 */

namespace Freshworkx\BmImageGallery\Event;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Event fired before view variables are assigned to the template.
 *
 * This event is dispatched in all controller actions (list, gallery, detail)
 * before the view receives its data.
 *
 * This event allows listeners to:
 * - Add custom template variables
 * - Modify existing view assignments
 * - Inject analytics or tracking data
 * - Add user context information
 * - Generate breadcrumbs
 * - Add SEO metadata
 * - Implement A/B testing logic
 * - Add navigation data
 */
final class BeforeRenderingEvent
{
    /**
     * @param array<string, mixed> $viewVariables
     */
    public function __construct(
        private array $viewVariables,
        private readonly string $action,
        private readonly ServerRequestInterface $request
    ) {
    }

    /**
     * Get all view variables that will be assigned to the template.
     *
     * @return array<string, mixed>
     */
    public function getViewVariables(): array
    {
        return $this->viewVariables;
    }

    /**
     * Set all view variables.
     * Use this to modify existing variables or add new ones.
     *
     * @param array<string, mixed> $viewVariables
     */
    public function setViewVariables(array $viewVariables): void
    {
        $this->viewVariables = $viewVariables;
    }

    /**
     * Add or update a single view variable.
     * This is a convenience method for adding single variables.
     */
    public function setViewVariable(string $key, mixed $value): void
    {
        $this->viewVariables[$key] = $value;
    }

    /**
     * Get the current action name.
     * Possible values: 'list', 'gallery', 'detail'
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Get the current PSR-7 request.
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
