<?php

declare(strict_types=1);

namespace App\Core\Document\Support;

use InvalidArgumentException;

final class PaperSizeRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | ISO A Series
            |--------------------------------------------------------------------------
            */

            'a0' => [
                'key' => 'a0',
                'label' => 'A0',
                'category' => 'print',
                'width' => 841.0,
                'height' => 1189.0,
                'unit' => 'mm',
            ],

            'a1' => [
                'key' => 'a1',
                'label' => 'A1',
                'category' => 'print',
                'width' => 594.0,
                'height' => 841.0,
                'unit' => 'mm',
            ],

            'a2' => [
                'key' => 'a2',
                'label' => 'A2',
                'category' => 'print',
                'width' => 420.0,
                'height' => 594.0,
                'unit' => 'mm',
            ],

            'a3' => [
                'key' => 'a3',
                'label' => 'A3',
                'category' => 'print',
                'width' => 297.0,
                'height' => 420.0,
                'unit' => 'mm',
            ],

            'a4' => [
                'key' => 'a4',
                'label' => 'A4',
                'category' => 'print',
                'width' => 210.0,
                'height' => 297.0,
                'unit' => 'mm',
            ],

            'a5' => [
                'key' => 'a5',
                'label' => 'A5',
                'category' => 'print',
                'width' => 148.0,
                'height' => 210.0,
                'unit' => 'mm',
            ],

            'a6' => [
                'key' => 'a6',
                'label' => 'A6',
                'category' => 'print',
                'width' => 105.0,
                'height' => 148.0,
                'unit' => 'mm',
            ],

            /*
            |--------------------------------------------------------------------------
            | North American Paper
            |--------------------------------------------------------------------------
            */

            'letter' => [
                'key' => 'letter',
                'label' => 'US Letter',
                'category' => 'print',
                'width' => 8.5,
                'height' => 11.0,
                'unit' => 'in',
            ],

            'legal' => [
                'key' => 'legal',
                'label' => 'US Legal',
                'category' => 'print',
                'width' => 8.5,
                'height' => 14.0,
                'unit' => 'in',
            ],

            'tabloid' => [
                'key' => 'tabloid',
                'label' => 'Tabloid',
                'category' => 'print',
                'width' => 11.0,
                'height' => 17.0,
                'unit' => 'in',
            ],

            /*
            |--------------------------------------------------------------------------
            | Identification Cards
            |--------------------------------------------------------------------------
            */

            'cr80' => [
                'key' => 'cr80',
                'label' => 'CR80 / ID-1',
                'category' => 'card',
                'width' => 85.60,
                'height' => 53.98,
                'unit' => 'mm',
            ],

            'id2' => [
                'key' => 'id2',
                'label' => 'ID-2',
                'category' => 'card',
                'width' => 105.0,
                'height' => 74.0,
                'unit' => 'mm',
            ],

            'id3' => [
                'key' => 'id3',
                'label' => 'ID-3',
                'category' => 'card',
                'width' => 125.0,
                'height' => 88.0,
                'unit' => 'mm',
            ],

            /*
            |--------------------------------------------------------------------------
            | Business Cards
            |--------------------------------------------------------------------------
            */

            'business-card-us' => [
                'key' => 'business-card-us',
                'label' => 'Business Card — US',
                'category' => 'card',
                'width' => 3.5,
                'height' => 2.0,
                'unit' => 'in',
            ],

            'business-card-eu' => [
                'key' => 'business-card-eu',
                'label' => 'Business Card — EU',
                'category' => 'card',
                'width' => 85.0,
                'height' => 55.0,
                'unit' => 'mm',
            ],

            /*
            |--------------------------------------------------------------------------
            | Badges
            |--------------------------------------------------------------------------
            */

            'badge-square' => [
                'key' => 'badge-square',
                'label' => 'Square Badge',
                'category' => 'badge',
                'width' => 1000.0,
                'height' => 1000.0,
                'unit' => 'px',
            ],

            'badge-landscape' => [
                'key' => 'badge-landscape',
                'label' => 'Landscape Badge',
                'category' => 'badge',
                'width' => 1200.0,
                'height' => 800.0,
                'unit' => 'px',
            ],

            'badge-portrait' => [
                'key' => 'badge-portrait',
                'label' => 'Portrait Badge',
                'category' => 'badge',
                'width' => 800.0,
                'height' => 1200.0,
                'unit' => 'px',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function get(
        string $key,
        string $orientation = 'portrait',
    ): array {
        $sizes = self::all();

        $key = strtolower(
            trim($key)
        );

        if (!isset($sizes[$key])) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported paper size "%s".',
                    $key
                )
            );
        }

        $size = $sizes[$key];

        return self::orient(
            $size,
            $orientation
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function custom(
        float $width,
        float $height,
        string $unit,
        string $orientation = 'portrait',
    ): array {
        if (
            $width <= 0 ||
            $height <= 0
        ) {
            throw new InvalidArgumentException(
                'Custom paper dimensions must be greater than zero.'
            );
        }

        $unit = strtolower(
            trim($unit)
        );

        if (
            !in_array(
                $unit,
                [
                    'mm',
                    'cm',
                    'in',
                    'px',
                ],
                true
            )
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported paper unit "%s".',
                    $unit
                )
            );
        }

        return self::orient(
            [
                'key' => 'custom',
                'label' => 'Custom',
                'category' => 'custom',
                'width' => $width,
                'height' => $height,
                'unit' => $unit,
            ],
            $orientation
        );
    }

    /**
     * @param array<string, mixed> $size
     *
     * @return array<string, mixed>
     */
    private static function orient(
        array $size,
        string $orientation,
    ): array {
        $orientation = strtolower(
            trim($orientation)
        );

        if (
            !in_array(
                $orientation,
                [
                    'portrait',
                    'landscape',
                ],
                true
            )
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported orientation "%s".',
                    $orientation
                )
            );
        }

        $width =
            (float) $size['width'];

        $height =
            (float) $size['height'];

        if (
            $orientation === 'portrait' &&
            $width > $height
        ) {
            [
                $width,
                $height,
            ] = [
                $height,
                $width,
            ];
        }

        if (
            $orientation === 'landscape' &&
            $height > $width
        ) {
            [
                $width,
                $height,
            ] = [
                $height,
                $width,
            ];
        }

        $size['width'] =
            $width;

        $size['height'] =
            $height;

        $size['orientation'] =
            $orientation;

        return $size;
    }
}