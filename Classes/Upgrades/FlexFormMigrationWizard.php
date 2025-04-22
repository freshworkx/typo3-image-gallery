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

namespace Freshworkx\BmImageGallery\Upgrades;

use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('bmImageGalleryFlexFormMigration')]
class FlexFormMigrationWizard implements UpgradeWizardInterface, ChattyInterface
{
    protected OutputInterface $output;

    public function __construct(
        private readonly ConnectionPool $connectionPool,
        private readonly FlexFormTools $flexFormTools
    ) {
    }

    public function getTitle(): string
    {
        return 'Migrate the flexForm of "bm_image_gallery" plugins.';
    }

    public function getDescription(): string
    {
        return "ATTENTION:\r\nPlease make sure that you have successfully run\r\n\"bmImageGalleryCTypeMigration\" wizard first!!!\r\n\r\n" .
        "Migrates flexForm of \"bm_image_gallery\" plugins depending on CType\r\nand copy file_collection values to tt_content.";
    }

    public function getPrerequisites(): array
    {
        return [];
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * @throws Exception
     */
    public function updateNecessary(): bool
    {
        $pluginsToUpdate = $this->findGalleryPluginsToUpdate();
        return (bool)count($pluginsToUpdate) > 0;
    }

    /**
     * @throws Exception
     */
    public function executeUpdate(): bool
    {
        $count = 0;
        $pluginsToUpdate = $this->findGalleryPluginsToUpdate();
        foreach ($pluginsToUpdate as $plugin) {
            try {
                $migratedPlugin = $this->migrate($plugin);
                $affectedRows = $this->updatePlugin($migratedPlugin);
                if ($affectedRows === 1) {
                    $count++;
                }
            } catch (\Exception $e) {
                $this->output->writeln($e->getMessage());
                return false;
            }
        }

        $this->output->writeln($count . ' of ' . count($pluginsToUpdate) . ' plugins have been migrated.');
        return true;
    }

    /**
     * @param array<string, mixed> $plugin
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function migrate(array $plugin): array
    {
        // parse flexForm to array
        $flexFormArray = $this->getFlexFormArray($plugin['pi_flexform']);
        if (is_array($flexFormArray) && $flexFormArray != []) {
            // extract fileCollection(s) from flexForm depending on CType
            $plugin['file_collections'] = $this->extractFileCollections($flexFormArray, $plugin['CType']);
            // migrate flexForm depending on CType
            $flexFormArray = $this->migrateFlexForm($flexFormArray, $plugin['CType']);
            // convert array back to flexForm
            $plugin['pi_flexform'] =  $this->flexFormTools->flexArray2Xml($flexFormArray);
            return $plugin;
        } else {
            throw new \Exception('xml2array() parsing error for plugin ' . $plugin['uid'], 1741701932);
        }
    }

    /**
     * @return array<string, mixed>|string
     */
    public function getFlexFormArray(string $piFlexForm): array|string
    {
        // If the parsing had errors, xml2array() returned a string with error message
        return GeneralUtility::xml2array($piFlexForm);
    }

    /**
     * @param array<string, mixed> $flexFormArray
     */
    public function extractFileCollections(array $flexFormArray, string $CType): string
    {
        // no collection, default value for CType 'bmimagegallery_gallerydetail'
        $fileCollections = '';

        return match ($CType) {
            // multiple collections
            'bmimagegallery_gallerylist' => $flexFormArray['data']['sDEF']['lDEF']['settings.collections']['vDEF'],
            // single collection
            'bmimagegallery_selectedgallery' => $flexFormArray['data']['sDEF']['lDEF']['settings.collection']['vDEF'],
            default => $fileCollections,
        };
    }

    /**
     * @param array<string, mixed> $flexFormArray
     * @return array<string, mixed>
     */
    public function migrateFlexForm(array $flexFormArray, string $CType): array
    {
        // merge settings from list to sDEF and remove list node
        $flexFormArray = $this->mergeFlexFormSettings($flexFormArray);

        // define allowed settings depending on CType
        $allowedSettings = [];
        switch ($CType) {
            case 'bmimagegallery_gallerylist':
                $mode = $flexFormArray['data']['sDEF']['lDEF']['settings.mode']['vDEF'];
                // default for mode 0 (same page) and 2 (no detail view)
                $allowedSettings = ['settings.mode', 'settings.orderBy', 'settings.sortingOrder', 'settings.maxItems'];
                if ($mode === '1') {
                    // mode 1 (selected page)
                    $allowedSettings = ['settings.mode', 'settings.galleryPage'];
                }

                break;
            case 'bmimagegallery_selectedgallery':
            case 'bmimagegallery_gallerydetail':
                $allowedSettings = ['settings.orderBy', 'settings.sortingOrder', 'settings.maxItems'];
                break;
        }

        // remove not allowed settings
        if ($allowedSettings !== []) {
            foreach ($flexFormArray['data']['sDEF']['lDEF'] as $setting => $value) {
                if (!in_array($setting, $allowedSettings)) {
                    unset($flexFormArray['data']['sDEF']['lDEF'][$setting]);
                }
            }
        }

        return $flexFormArray;
    }

    /**
     * @param array<string, mixed> $flexFormArray
     * @return array<string, mixed>
     */
    public function mergeFlexFormSettings(array $flexFormArray): array
    {
        ArrayUtility::mergeRecursiveWithOverrule($flexFormArray['data']['sDEF']['lDEF'], $flexFormArray['data']['list']['lDEF']);
        unset($flexFormArray['data']['list']);

        return $flexFormArray;
    }

    /**
     * @return array<array<string,mixed>>
     * @throws Exception
     */
    public function findGalleryPluginsToUpdate(): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        return $queryBuilder
            ->select('uid', 'CType', 'pi_flexform', 'file_collections')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->like(
                    'CType',
                    $queryBuilder->createNamedParameter(
                        '%' . $queryBuilder->escapeLikeWildcards('bmimagegallery_') . '%',
                        Connection::PARAM_STR
                    )
                ),
                $queryBuilder->expr()->like(
                    'pi_flexform',
                    $queryBuilder->createNamedParameter(
                        '%' . $queryBuilder->escapeLikeWildcards('<sheet index="list">') . '%',
                        Connection::PARAM_STR
                    )
                )
            )->executeQuery()->fetchAllAssociative();
    }

    /**
     * @param array<string, mixed> $plugin
     */
    public function updatePlugin(array $plugin): int
    {
        return $this->connectionPool
            ->getConnectionForTable('tt_content')
            ->update(
                'tt_content',
                [
                    'pi_flexform' => $plugin['pi_flexform'],
                    'file_collections' => $plugin['file_collections'],
                ],
                ['uid' => $plugin['uid']]
            );
    }
}
