<?php

declare(strict_types=1);

namespace Freshworkx\BmImageGallery\Tests\Unit\Upgrades;

use Freshworkx\BmImageGallery\Upgrades\FlexFormMigrationWizard;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(FlexFormMigrationWizard::class)]
class FlexFormMigrationWizardTest extends UnitTestCase
{
    protected FlexFormMigrationWizard $flexFormMigrationWizard;

    protected function setUp(): void
    {
        parent::setUp();

        $this->flexFormMigrationWizard = $this->getAccessibleMock(
            FlexFormMigrationWizard::class,
            ['getFlexFormArray', 'mergeFlexFormSettings'],
            [],
            '',
            false,
        );
    }

    #[Test]
    public function migrateThrowsExceptionIfFlexFormIsMalformed(): void
    {
        $malformedXml = 'malformed_xml_string';

        // @phpstan-ignore method.notFound
        $this->flexFormMigrationWizard
            ->expects($this->once())
            ->method('getFlexFormArray')
            ->with($malformedXml)
            ->willReturn('parsing_error_message');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('xml2array() parsing error for plugin 123');
        $this->expectExceptionCode(1741701932);

        $plugin = [
            'uid' => 123,
            'pi_flexform' => $malformedXml
        ];
        $this->flexFormMigrationWizard->migrate($plugin);
    }

    #[Test]
    #[DataProvider('fileCollectionsDataProvider')]
    public function extractFileCollectionsReturnValueDependOnCtype(string $CType, string $expected): void
    {
        $flexFormArray = [
            'data' => [
                'sDEF' => [
                    'lDEF' => [
                        'settings.collections' => [
                            'vDEF' => '1,2,3'
                        ],
                        'settings.collection' => [
                            'vDEF' => '1'
                        ]
                    ]
                ]
            ]
        ];

        $actual = $this->flexFormMigrationWizard->extractFileCollections($flexFormArray, $CType);
        $this->assertEquals($expected, $actual);
    }

    // @phpstan-ignore missingType.iterableValue
    public static function fileCollectionsDataProvider(): array
    {
        return [
            'detail' => [
                'bmimagegallery_gallerydetail', // CType
                '' // expected
            ],
            'list' => [
                'bmimagegallery_gallerylist',
                '1,2,3'
            ],
            'gallery' => [
                'bmimagegallery_selectedgallery',
                '1'
            ]
        ];
    }

    /**
     * @param array<int, list<string>> $expected
     */
    #[Test]
    #[DataProvider('flexFormDataProvider')]
    public function migrateFlexFormReturnValueDependOnCtype(string $CType, string $mode, array $expected): void
    {
        $flexFormArrayToMigrate = [
            'data' => [
                'list' => [
                    'lDEF' => [
                        'settings.maxItems' => [
                            'vDEF' => '0'
                        ],
                        'settings.orderBy' => [
                            'vDEF' => 'default'
                        ],
                        'settings.sortingOrder' => [
                            'vDEF' => 'ascending'
                        ]
                    ]
                ],
                'sDEF' => [
                    'lDEF' => [
                        'settings.collection' => [
                            'vDEF' => '345'
                        ],
                        'settings.collections' => [
                            'vDEF' => '6,7,8'
                        ],
                        'settings.mode' => [
                            'vDEF' => $mode
                        ],
                        'settings.galleryPage' => [
                            'vDEF' => '56'
                        ]
                    ]
                ]
            ]
        ];

        $flexFormMerged = [
            'data' => [
                'sDEF' => [
                    'lDEF' => [
                        'settings.collection' => [
                            'vDEF' => $flexFormArrayToMigrate['data']['sDEF']['lDEF']['settings.collection']['vDEF']
                        ],
                        'settings.collections' => [
                            'vDEF' => $flexFormArrayToMigrate['data']['sDEF']['lDEF']['settings.collections']['vDEF']
                        ],
                        'settings.mode' => [
                            'vDEF' => $flexFormArrayToMigrate['data']['sDEF']['lDEF']['settings.mode']['vDEF']
                        ],
                        'settings.galleryPage' => [
                            'vDEF' => $flexFormArrayToMigrate['data']['sDEF']['lDEF']['settings.galleryPage']['vDEF']
                        ],
                        'settings.maxItems' => [
                            'vDEF' => $flexFormArrayToMigrate['data']['list']['lDEF']['settings.maxItems']['vDEF']
                        ],
                        'settings.orderBy' => [
                            'vDEF' => $flexFormArrayToMigrate['data']['list']['lDEF']['settings.orderBy']['vDEF']
                        ],
                        'settings.sortingOrder' => [
                            'vDEF' => $flexFormArrayToMigrate['data']['list']['lDEF']['settings.sortingOrder']['vDEF']
                        ]
                    ]
                ]
            ]
        ];

        // @phpstan-ignore method.notFound
        $this->flexFormMigrationWizard
            ->expects($this->once())
            ->method('mergeFlexFormSettings')
            ->with($flexFormArrayToMigrate)
            ->willReturn($flexFormMerged);

        $actual = $this->flexFormMigrationWizard->migrateFlexForm($flexFormArrayToMigrate, $CType);
        $this->assertEquals($expected, $actual);
    }

    // @phpstan-ignore missingType.iterableValue
    public static function flexFormDataProvider(): array
    {
        return [
            'detail' => [
                'bmimagegallery_gallerydetail', // CType
                '0', // mode
                [ // expected
                    'data' => [
                        'sDEF' => [
                            'lDEF' => [
                                'settings.maxItems' => ['vDEF' => '0'],
                                'settings.orderBy' => ['vDEF' => 'default'],
                                'settings.sortingOrder' => ['vDEF' => 'ascending'],
                            ]
                        ]
                    ]
                ]
            ],
            'list with mode 0' => [
                'bmimagegallery_gallerylist',
                '0',
                [
                    'data' => [
                        'sDEF' => [
                            'lDEF' => [
                                'settings.mode' => ['vDEF' => '0'],
                                'settings.maxItems' => ['vDEF' => '0'],
                                'settings.orderBy' => ['vDEF' => 'default'],
                                'settings.sortingOrder' => ['vDEF' => 'ascending'],
                            ]
                        ]
                    ]
                ]
            ],
            'list with mode 1' => [
                'bmimagegallery_gallerylist',
                '1',
                [
                    'data' => [
                        'sDEF' => [
                            'lDEF' => [
                                'settings.mode' => ['vDEF' => '1'],
                                'settings.galleryPage' => ['vDEF' => '56'],
                            ]
                        ]
                    ]
                ]
            ],
            'list with mode 2' => [
                'bmimagegallery_gallerylist',
                '2',
                [
                    'data' => [
                        'sDEF' => [
                            'lDEF' => [
                                'settings.mode' => ['vDEF' => '2'],
                                'settings.maxItems' => ['vDEF' => '0'],
                                'settings.orderBy' => ['vDEF' => 'default'],
                                'settings.sortingOrder' => ['vDEF' => 'ascending'],
                            ]
                        ]
                    ]
                ]
            ],
            'gallery' => [
                'bmimagegallery_selectedgallery',
                '0',
                [
                    'data' => [
                        'sDEF' => [
                            'lDEF' => [
                                'settings.maxItems' => ['vDEF' => '0'],
                                'settings.orderBy' => ['vDEF' => 'default'],
                                'settings.sortingOrder' => ['vDEF' => 'ascending'],
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
