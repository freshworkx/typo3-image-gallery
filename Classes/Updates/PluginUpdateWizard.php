<?php

/*
 * This file is part of the "Image Gallery" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Jens Neumann <info@freshworkx.de>
 */

namespace Freshworkx\BmImageGallery\Updates;

use TYPO3\CMS\Core\Database\Connection;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('bmImageGallery_plugin')]
class PluginUpdateWizard implements UpgradeWizardInterface
{
    private const SOURCE_LIST_TYPE = 'bmimagegallery_list';

    private const TARGET_LIST_TYPES = [
        'List->list;List->gallery' => 'bmimagegallery_gallerylist',
        'List->gallery' => 'bmimagegallery_gallerydetail',
        'List->selectedGallery' => 'bmimagegallery_selectedgallery',
    ];

    /**
     * @var OutputInterface
     */
    protected $output;

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * Return the speaking name of this wizard
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'TYPO3 Image Gallery: Split plugins';
    }

    /**
     * Return the description for this wizard
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Updates existing gallery plugins, transforms their flexform structure and unwind their switchable controller actions.'; // phpcs:ignore
    }

    /**
     * Execute the update
     *
     * Called when a wizard reports that an update is necessary
     *
     * @return bool
     */
    public function executeUpdate(): bool
    {
        $flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        foreach ($this->getPlugins() as $plugin) {
            $flexForm = GeneralUtility::xml2array($plugin['pi_flexform']);
            $listType = $this->getTargetListType($flexForm);
            $newFlexFormData = $this->transformFlexFormStructure($flexForm, $listType);
            $flexForm = $flexFormTools->flexArray2Xml($newFlexFormData, true);

            $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
            $queryBuilder
                ->update('tt_content')
                ->set('pi_flexform', $flexForm ?? '')
                ->set('list_type', $listType)
                ->where($queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($plugin['uid'], Connection::PARAM_INT)
                ))
                ->executeStatement();

            $this->output->writeln('Updated plugin with UID ' . $plugin['uid']);
        }

        return true;
    }

    /**
     * Is an update necessary?
     *
     * Is used to determine whether a wizard needs to be run.
     * Check if data for migration exists.
     *
     * @return bool
     */
    public function updateNecessary(): bool
    {
        return count($this->getPlugins()) > 0;
    }

    /**
     * Returns an array of class names of prerequisite classes
     *
     * This way a wizard can define dependencies like "database up-to-date" or
     * "reference index updated"
     *
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [];
    }

    protected function getPlugins(): array
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content')
            ->select('*')
            ->from('tt_content')
            ->where(sprintf('list_type = "%s"', static::SOURCE_LIST_TYPE))
            ->executeQuery()
            ->fetchAllAssociative();
    }

    protected function getTargetListType(array $flexForm): string
    {
        $controllerAction = $flexForm['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
        $controllerAction = htmlspecialchars_decode((string) $controllerAction);

        return static::TARGET_LIST_TYPES[$controllerAction];
    }

    protected function transformFlexFormStructure(array $flexForm, string $listType): array
    {
        switch ($listType) {
            case 'bmimagegallery_gallerylist':
                $mode = $flexForm['data']['sDEF']['lDEF']['settings.mode']['vDEF'] ?? null;

                $sDEFSettings = [
                    'settings.collections' => [
                        'vDEF' => $flexForm['data']['sDEF']['lDEF']['settings.collections']['vDEF'],
                    ],
                    'settings.mode' => [
                        'vDEF' => $mode,
                    ],
                ];

                if ($mode == 1) {
                    $sDEFSettings['settings.galleryPage'] = [
                        'vDEF' => $flexForm['data']['sDEF']['lDEF']['settings.galleryPage']['vDEF'],
                    ];
                }

                break;

            case 'bmimagegallery_selectedgallery':
                $sDEFSettings = [
                    'settings.collection' => [
                        'vDEF' => $flexForm['data']['sDEF']['lDEF']['settings.collection']['vDEF'],
                    ],
                ];
                break;
        }

        $data = [
            'data' => [
                'sDEF' => [
                    'lDEF' => $sDEFSettings ?? [],
                ],
            ],
        ];

        if (($mode ?? null) != 1) {
            $data['data']['list']['lDEF'] = [
                'settings.maxItems' => [
                    'vDEF' => $flexForm['data']['sDEF']['list']['settings.maxItems']['vDEF'] ?? 0,
                ],
                'settings.orderBy' => [
                    'vDEF' => $flexForm['data']['sDEF']['list']['settings.orderBy']['vDEF'],
                ],
                'settings.sortingOrder' => [
                    'vDEF' => $flexForm['data']['sDEF']['list']['settings.sortingOrder']['vDEF'],
                ],
            ];
        }

        return $data;
    }
}
