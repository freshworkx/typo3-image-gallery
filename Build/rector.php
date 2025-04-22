<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/../Build',
        __DIR__ . '/../Classes',
        __DIR__ . '/../Configuration',
        __DIR__ . '/../Tests',
    ])
    ->withPhpSets(false, true)
    ->withPreparedSets(true, true, true)
    ->withSets([
        LevelSetList::UP_TO_PHP_84,
        Typo3LevelSetList::UP_TO_TYPO3_13,
        PHPUnitSetList::PHPUNIT_110

    ])
    ->withPhpVersion(PhpVersion::PHP_82)
;
