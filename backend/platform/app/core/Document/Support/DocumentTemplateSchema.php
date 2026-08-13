<?php

declare(strict_types=1);

namespace App\Core\Document\Support;

use InvalidArgumentException;

final class DocumentTemplateSchema
{
    public const VERSION = 2;

    public const DOCUMENT_TYPES = [
        'certificate',
        'diploma',
        'badge',
        'id_card',
        'training_card',
        'custom',
    ];

    public const LANGUAGES = [
        'en',
        'fr',
        'en-fr',
    ];

    public const ORIENTATIONS = [
        'portrait',
        'landscape',
    ];

    /**
     * @return array<string, string>
     */
    public static function documentTypes(): array
    {
        return [
            'certificate' =>
                'Certificate',

            'diploma' =>
                'Diploma',

            'badge' =>
                'Badge',

            'id_card' =>
                'ID Card',

            'training_card' =>
                'Training Card',

            'custom' =>
                'Custom Document',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function languages(): array
    {
        return [
            'en' =>
                'English',

            'fr' =>
                'Français',

            'en-fr' =>
                'English / Français',
        ];
    }

    /**
     * Return the recommended starting configuration
     * for a document type.
     *
     * @return array<string, mixed>
     */
    public static function defaults(
        string $documentType,
        string $language = 'en',
    ): array {
        self::validateDocumentType(
            $documentType
        );

        self::validateLanguage(
            $language
        );

        return match (
            $documentType
        ) {
            'certificate' =>
                self::certificateDefaults(
                    $language
                ),

            'diploma' =>
                self::diplomaDefaults(
                    $language
                ),

            'badge' =>
                self::badgeDefaults(
                    $language
                ),

            'id_card' =>
                self::idCardDefaults(
                    $language
                ),

            'training_card' =>
                self::trainingCardDefaults(
                    $language
                ),

            'custom' =>
                self::customDefaults(
                    $language
                ),

            default =>
                throw new InvalidArgumentException(
                    'Unsupported document type.'
                ),
        };
    }

    /**
     * Build a complete V2 schema.
     *
     * @param array<string, mixed> $paper
     * @param array<int, array<string, mixed>> $pages
     *
     * @return array<string, mixed>
     */
    public static function build(
        string $documentType,
        string $language,
        array $paper,
        array $pages,
    ): array {
        self::validateDocumentType(
            $documentType
        );

        self::validateLanguage(
            $language
        );

        if ($pages === []) {
            throw new InvalidArgumentException(
                'A document template must contain at least one page.'
            );
        }

        return [
            'schema_version' =>
                self::VERSION,

            'engine' =>
                'fabric',

            'engine_version' =>
                7,

            'document_type' =>
                $documentType,

            'language' =>
                $language,

            'paper' =>
                $paper,

            'pages' =>
                array_values(
                    $pages
                ),
        ];
    }

    /**
     * Convert an existing Fabric V1 canvas into a
     * V2-compatible schema without modifying the
     * original database record.
     *
     * @param array<string, mixed> $canvas
     *
     * @return array<string, mixed>
     */
    public static function fromLegacyCanvas(
        array $canvas,
        string $documentType = 'certificate',
        string $language = 'en',
        string $paperSize = 'custom',
        string $orientation = 'landscape',
        ?float $paperWidth = null,
        ?float $paperHeight = null,
        string $paperUnit = 'px',
    ): array {
        self::validateDocumentType(
            $documentType
        );

        self::validateLanguage(
            $language
        );

        $canvasWidth =
            self::positiveFloat(
                $canvas['canvas_width']
                    ?? null,
                1000
            );

        $canvasHeight =
            self::positiveFloat(
                $canvas['canvas_height']
                    ?? null,
                650
            );

        if (
            $paperWidth !== null &&
            $paperHeight !== null
        ) {
            $paper =
                PaperSizeRegistry::custom(
                    $paperWidth,
                    $paperHeight,
                    $paperUnit,
                    $orientation
                );

            $paper['preset'] =
                $paperSize;
        } elseif (
            $paperSize !== 'custom'
        ) {
            $paper =
                PaperSizeRegistry::get(
                    $paperSize,
                    $orientation
                );

            $paper['preset'] =
                $paperSize;
        } else {
            /*
             * Old templates did not have physical
             * paper dimensions.
             *
             * Preserve their existing coordinate
             * system as pixels instead of pretending
             * it represents A4 or Letter.
             */
            $paper = [
                'key' =>
                    'custom',

                'preset' =>
                    'custom',

                'label' =>
                    'Legacy Custom',

                'category' =>
                    'custom',

                'width' =>
                    $canvasWidth,

                'height' =>
                    $canvasHeight,

                'unit' =>
                    'px',

                'orientation' =>
                    $orientation,
            ];
        }

        return self::build(
            documentType:
                $documentType,

            language:
                $language,

            paper:
                $paper,

            pages: [
                self::page(
                    key:
                        'front',

                    canvasWidth:
                        $canvasWidth,

                    canvasHeight:
                        $canvasHeight,

                    background:
                        is_string(
                            $canvas['background']
                                ?? null
                        )
                            ? $canvas['background']
                            : '#ffffff',

                    objects:
                        is_array(
                            $canvas['objects']
                                ?? null
                        )
                            ? $canvas['objects']
                            : [],
                ),
            ],
        );
    }

    /**
     * @param array<int, mixed> $objects
     *
     * @return array<string, mixed>
     */
    public static function page(
        string $key,
        float $canvasWidth,
        float $canvasHeight,
        string $background = '#ffffff',
        array $objects = [],
    ): array {
        if (
            $canvasWidth <= 0 ||
            $canvasHeight <= 0
        ) {
            throw new InvalidArgumentException(
                'Canvas dimensions must be greater than zero.'
            );
        }

        return [
            'key' =>
                $key,

            'canvas_width' =>
                $canvasWidth,

            'canvas_height' =>
                $canvasHeight,

            'background' =>
                $background,

            'objects' =>
                array_values(
                    $objects
                ),
        ];
    }

    public static function requiresBackSide(
        string $documentType,
    ): bool {
        return in_array(
            $documentType,
            [
                'id_card',
                'training_card',
            ],
            true
        );
    }

    private static function certificateDefaults(
        string $language,
    ): array {
        $paper =
            PaperSizeRegistry::get(
                'a4',
                'landscape'
            );

        $paper['preset'] =
            'a4';

        return self::build(
            'certificate',
            $language,
            $paper,
            [
                self::page(
                    'front',
                    1414,
                    1000
                ),
            ]
        );
    }

    private static function diplomaDefaults(
        string $language,
    ): array {
        $paper =
            PaperSizeRegistry::get(
                'a4',
                'portrait'
            );

        $paper['preset'] =
            'a4';

        return self::build(
            'diploma',
            $language,
            $paper,
            [
                self::page(
                    'front',
                    1000,
                    1414
                ),
            ]
        );
    }

    private static function badgeDefaults(
        string $language,
    ): array {
        $paper =
            PaperSizeRegistry::get(
                'badge-square',
                'portrait'
            );

        $paper['preset'] =
            'badge-square';

        return self::build(
            'badge',
            $language,
            $paper,
            [
                self::page(
                    'front',
                    1000,
                    1000
                ),
            ]
        );
    }

    private static function idCardDefaults(
        string $language,
    ): array {
        $paper =
            PaperSizeRegistry::get(
                'cr80',
                'landscape'
            );

        $paper['preset'] =
            'cr80';

        return self::build(
            'id_card',
            $language,
            $paper,
            [
                self::page(
                    'front',
                    1011,
                    638
                ),

                self::page(
                    'back',
                    1011,
                    638
                ),
            ]
        );
    }

    private static function trainingCardDefaults(
        string $language,
    ): array {
        $paper =
            PaperSizeRegistry::get(
                'cr80',
                'landscape'
            );

        $paper['preset'] =
            'cr80';

        return self::build(
            'training_card',
            $language,
            $paper,
            [
                self::page(
                    'front',
                    1011,
                    638
                ),

                self::page(
                    'back',
                    1011,
                    638
                ),
            ]
        );
    }

    private static function customDefaults(
        string $language,
    ): array {
        $paper =
            PaperSizeRegistry::custom(
                1000,
                650,
                'px',
                'landscape'
            );

        $paper['preset'] =
            'custom';

        return self::build(
            'custom',
            $language,
            $paper,
            [
                self::page(
                    'front',
                    1000,
                    650
                ),
            ]
        );
    }

    private static function validateDocumentType(
        string $documentType,
    ): void {
        if (
            !in_array(
                $documentType,
                self::DOCUMENT_TYPES,
                true
            )
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported document type "%s".',
                    $documentType
                )
            );
        }
    }

    private static function validateLanguage(
        string $language,
    ): void {
        if (
            !in_array(
                $language,
                self::LANGUAGES,
                true
            )
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported template language "%s".',
                    $language
                )
            );
        }
    }

    private static function positiveFloat(
        mixed $value,
        float $fallback,
    ): float {
        if (
            !is_numeric(
                $value
            )
        ) {
            return $fallback;
        }

        $number =
            (float) $value;

        return $number > 0
            ? $number
            : $fallback;
    }
}