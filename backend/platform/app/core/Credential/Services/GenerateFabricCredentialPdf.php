<?php

declare(strict_types=1);

namespace App\Core\Credential\Services;

use App\Core\Credential\Models\Credential;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use RuntimeException;

final class GenerateFabricCredentialPdf
{
    public function execute(
        Credential $credential,
    ): string {
        $metadata =
            is_array($credential->metadata)
                ? $credential->metadata
                : [];

        $fabric =
            $metadata['fabric']
                ?? null;

        if (!is_array($fabric)) {
            throw new RuntimeException(
                'Fabric credential snapshot is missing.'
            );
        }

        $canvas =
            $fabric['canvas']
                ?? null;

        if (!is_array($canvas)) {
            throw new RuntimeException(
                'Fabric credential canvas is missing.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve Render Page
        |--------------------------------------------------------------------------
        |
        | V2 templates keep authoritative content in pages[].
        |
        | PDF credentials currently render one physical page. For single-page
        | documents this is Front. For legacy V1 documents we fall back to the
        | top-level canvas.
        |
        */

        $page =
            $this->resolveRenderPage(
                $canvas
            );

        $width =
            $this->positiveNumber(
                $page['canvas_width']
                    ?? $canvas['canvas_width']
                    ?? null,
                1000
            );

        $height =
            $this->positiveNumber(
                $page['canvas_height']
                    ?? $canvas['canvas_height']
                    ?? null,
                650
            );

        $objects =
            $this->prepareObjects(
                $page['objects']
                    ?? $canvas['objects']
                    ?? [],
                $credential
            );

        $background =
            is_string(
                $page['background']
                    ?? $canvas['background']
                    ?? null
            )
                ? (
                    $page['background']
                    ?? $canvas['background']
                )
                : '#ffffff';

        /*
        |--------------------------------------------------------------------------
        | Output Directory
        |--------------------------------------------------------------------------
        */

        $directory =
            storage_path(
                'app/public/certificates'
            );

        if (
            !File::exists(
                $directory
            )
        ) {
            File::makeDirectory(
                $directory,
                0755,
                true
            );
        }

        $filename =
            $credential
                ->credential_number
            . '.pdf';

        $filepath =
            $directory
            . DIRECTORY_SEPARATOR
            . $filename;

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        Pdf::loadView(
            'pdf.fabric-credential',
            [
                'credential' =>
                    $credential,

                'canvasWidth' =>
                    $width,

                'canvasHeight' =>
                    $height,

                'background' =>
                    $background,

                'objects' =>
                    $objects,
            ]
        )
            ->setPaper(
                [
                    0,
                    0,
                    $width,
                    $height,
                ]
            )
            ->save(
                $filepath
            );

        $credential->update([
            'pdf_path' =>
                'certificates/'
                . $filename,
        ]);

        return $filepath;
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Render Page
    |--------------------------------------------------------------------------
    */

    private function resolveRenderPage(
        array $canvas,
    ): array {
        $pages =
            $canvas['pages']
                ?? null;

        if (
            !is_array($pages) ||
            $pages === []
        ) {
            return $canvas;
        }

        /*
         * Prefer Front explicitly.
         */

        foreach (
            $pages as $page
        ) {
            if (
                is_array($page) &&
                (
                    $page['key']
                        ?? null
                ) === 'front'
            ) {
                return $page;
            }
        }

        /*
         * Otherwise use first valid page.
         */

        foreach (
            $pages as $page
        ) {
            if (
                is_array($page)
            ) {
                return $page;
            }
        }

        return $canvas;
    }

    /*
    |--------------------------------------------------------------------------
    | Prepare Objects
    |--------------------------------------------------------------------------
    */

    /**
     * @return array<int, array<string, mixed>>
     */
    private function prepareObjects(
        mixed $objects,
        Credential $credential,
    ): array {
        if (
            !is_array(
                $objects
            )
        ) {
            return [];
        }

        $prepared = [];

        foreach (
            $objects as $object
        ) {
            if (
                !is_array(
                    $object
                )
            ) {
                continue;
            }

            $type =
                (string) (
                    $object['type']
                        ?? ''
                );

            if (
                $type === ''
            ) {
                continue;
            }

            $scaleX =
                $this->number(
                    $object['scaleX']
                        ?? null,
                    1
                );

            $scaleY =
                $this->number(
                    $object['scaleY']
                        ?? null,
                    1
                );

            $width =
                max(
                    0,
                    $this->number(
                        $object['width']
                            ?? null,
                        0
                    )
                );

            $height =
                max(
                    0,
                    $this->number(
                        $object['height']
                            ?? null,
                        0
                    )
                );

            $renderWidth =
                $width
                * $scaleX;

            $renderHeight =
                $height
                * $scaleY;

            $left =
                $this->number(
                    $object['left']
                        ?? null,
                    0
                );

            $top =
                $this->number(
                    $object['top']
                        ?? null,
                    0
                );

            $originX =
                (string) (
                    $object['originX']
                        ?? 'left'
                );

            $originY =
                (string) (
                    $object['originY']
                        ?? 'top'
                );

            if (
                $originX ===
                'center'
            ) {
                $left -=
                    $renderWidth / 2;
            } elseif (
                $originX ===
                'right'
            ) {
                $left -=
                    $renderWidth;
            }

            if (
                $originY ===
                'center'
            ) {
                $top -=
                    $renderHeight / 2;
            } elseif (
                $originY ===
                'bottom'
            ) {
                $top -=
                    $renderHeight;
            }

            /*
            |--------------------------------------------------------------------------
            | Verification QR
            |--------------------------------------------------------------------------
            |
            | The designer stores the QR position as a normal Fabric object,
            | usually a Rect.
            |
            | At PDF generation time it becomes an Image while retaining
            | geometry.
            |
            */

            $isVerificationQr =
                (
                    $object[
                        'awakenType'
                    ]
                    ?? null
                ) ===
                'verification-qr';

            $renderType =
                $isVerificationQr
                    ? 'Image'
                    : $type;

            $preparedObject = [
                'type' =>
                    $renderType,

                'left' =>
                    $left,

                'top' =>
                    $top,

                'width' =>
                    $renderWidth,

                'height' =>
                    $renderHeight,

                'angle' =>
                    $this->number(
                        $object['angle']
                            ?? null,
                        0
                    ),

                'opacity' =>
                    $this->number(
                        $object['opacity']
                            ?? null,
                        1
                    ),

                'visible' =>
                    (
                        $object['visible']
                            ?? true
                    ) !== false,

                'fill' =>
                    $object['fill']
                        ?? '#000000',

                'stroke' =>
                    $object['stroke']
                        ?? null,

                'strokeWidth' =>
                    $this->number(
                        $object[
                            'strokeWidth'
                        ]
                            ?? null,
                        0
                    ),
            ];

            /*
            |--------------------------------------------------------------------------
            | Text
            |--------------------------------------------------------------------------
            */

            if (
                $type === 'Text' ||
                $type === 'IText' ||
                $type === 'Textbox'
            ) {
                $preparedObject[
                    'text'
                ] =
                    $this->resolveText(
                        (string) (
                            $object['text']
                                ?? ''
                        ),
                        $credential
                    );

                $preparedObject[
                    'fontFamily'
                ] =
                    (string) (
                        $object[
                            'fontFamily'
                        ]
                            ?? 'Arial'
                    );

                $preparedObject[
                    'fontSize'
                ] =
                    $this->number(
                        $object[
                            'fontSize'
                        ]
                            ?? null,
                        16
                    );

                $preparedObject[
                    'fontWeight'
                ] =
                    (string) (
                        $object[
                            'fontWeight'
                        ]
                            ?? 'normal'
                    );

                $preparedObject[
                    'fontStyle'
                ] =
                    (string) (
                        $object[
                            'fontStyle'
                        ]
                            ?? 'normal'
                    );

                $preparedObject[
                    'textAlign'
                ] =
                    (string) (
                        $object[
                            'textAlign'
                        ]
                            ?? 'left'
                    );

                $preparedObject[
                    'underline'
                ] =
                    (bool) (
                        $object[
                            'underline'
                        ]
                            ?? false
                    );

                $preparedObject[
                    'linethrough'
                ] =
                    (bool) (
                        $object[
                            'linethrough'
                        ]
                            ?? false
                    );

                $preparedObject[
                    'lineHeight'
                ] =
                    $this->number(
                        $object[
                            'lineHeight'
                        ]
                            ?? null,
                        1.16
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            |
            | Includes ordinary Fabric images and verification QR placeholders
            | converted to Image above.
            |
            */

            if (
                $renderType ===
                'Image'
            ) {
                $preparedObject[
                    'src'
                ] =
                    $this->resolveImageSource(
                        $object,
                        $credential
                    );

                /*
                 * Never render an empty QR
                 * placeholder rectangle.
                 */

                if (
                    $isVerificationQr &&
                    empty(
                        $preparedObject[
                            'src'
                        ]
                    )
                ) {
                    continue;
                }
            }

            $prepared[] =
                $preparedObject;
        }

        return $prepared;
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Text
    |--------------------------------------------------------------------------
    */

    private function resolveText(
        string $text,
        Credential $credential,
    ): string {
        if (
            $text === ''
        ) {
            return '';
        }

        /*
         * Normally Fabric issuance has
         * already resolved variables.
         *
         * These replacements are a
         * defensive final pass.
         */

        $metadata =
            is_array(
                $credential->metadata
            )
                ? $credential->metadata
                : [];

        $fabric =
            is_array(
                $metadata['fabric']
                    ?? null
            )
                ? $metadata['fabric']
                : [];

        $organization =
            is_array(
                $fabric[
                    'organization'
                ]
                    ?? null
            )
                ? $fabric[
                    'organization'
                ]
                : [];

        $participant =
            $credential
                ->participant;

        $program =
            $credential
                ->program;

        $participantName =
            trim(
                (string) (
                    $participant
                        ->first_name
                        ?? ''
                )
                . ' '
                . (string) (
                    $participant
                        ->last_name
                        ?? ''
                )
            );

        $issuedDate =
            optional(
                $credential
                    ->issued_at
            )
                ->toDateString();

        $expiresDate =
            optional(
                $credential
                    ->expires_at
            )
                ->toDateString();

        $replacements = [
            '{{participant.name}}' =>
                $participantName,

            '{{participant.first_name}}' =>
                (string) (
                    $participant
                        ->first_name
                        ?? ''
                ),

            '{{participant.last_name}}' =>
                (string) (
                    $participant
                        ->last_name
                        ?? ''
                ),

            '{{participant.email}}' =>
                (string) (
                    $participant
                        ->email
                        ?? ''
                ),

            '{{participant.code}}' =>
                (string) (
                    $participant
                        ->participant_code
                        ?? ''
                ),

            '{{program.title}}' =>
                (string) (
                    $program
                        ->title
                        ?? ''
                ),

            '{{credential.number}}' =>
                (string)
                    $credential
                        ->credential_number,

            '{{credential.verification_code}}' =>
                (string)
                    $credential
                        ->verification_code,

            '{{credential.verification_url}}' =>
                (string)
                    $credential
                        ->verification_url,

            '{{credential.issue_date}}' =>
                (string)
                    $issuedDate,

            '{{credential.issued_at}}' =>
                (string)
                    $issuedDate,

            '{{credential.expiry_date}}' =>
                (string)
                    $expiresDate,

            '{{organization.name}}' =>
                (string) (
                    $organization[
                        'name'
                    ]
                        ?? ''
                ),
        ];

        return strtr(
            $text,
            $replacements
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Image Source
    |--------------------------------------------------------------------------
    */

    private function resolveImageSource(
        array $object,
        Credential $credential,
    ): ?string {
        /*
        |--------------------------------------------------------------------------
        | Verification QR
        |--------------------------------------------------------------------------
        */

        if (
            (
                $object[
                    'awakenType'
                ]
                    ?? null
            ) ===
            'verification-qr'
        ) {
            /*
             * Prefer immutable issuance
             * metadata stored on the object.
             */

            $objectQrPath =
                $object[
                    'awakenQrCodePath'
                ]
                    ?? null;

            $qrPath =
                is_string(
                    $objectQrPath
                ) &&
                $objectQrPath !== ''
                    ? $objectQrPath
                    : $credential
                        ->qr_code_path;

            if (
                is_string(
                    $qrPath
                ) &&
                $qrPath !== ''
            ) {
                $absolute =
                    storage_path(
                        'app/public/'
                        . ltrim(
                            $qrPath,
                            '/'
                        )
                    );

                if (
                    is_file(
                        $absolute
                    )
                ) {
                    return
                        $this->fileToDataUri(
                            $absolute
                        );
                }
            }

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Normal Fabric Image
        |--------------------------------------------------------------------------
        */

        $src =
            $object['src']
                ?? null;

        if (
            !is_string(
                $src
            ) ||
            $src === ''
        ) {
            return null;
        }

        if (
            str_starts_with(
                $src,
                'data:'
            )
        ) {
            return $src;
        }

        /*
         * Local Laravel storage URL.
         */

        $path =
            parse_url(
                $src,
                PHP_URL_PATH
            );

        if (
            is_string(
                $path
            ) &&
            str_starts_with(
                $path,
                '/storage/'
            )
        ) {
            $absolute =
                public_path(
                    ltrim(
                        $path,
                        '/'
                    )
                );

            if (
                is_file(
                    $absolute
                )
            ) {
                return
                    $this->fileToDataUri(
                        $absolute
                    );
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | File -> Data URI
    |--------------------------------------------------------------------------
    */

    private function fileToDataUri(
        string $path,
    ): ?string {
        if (
            !is_file(
                $path
            )
        ) {
            return null;
        }

        $contents =
            @file_get_contents(
                $path
            );

        if (
            $contents === false
        ) {
            return null;
        }

        $mime =
            mime_content_type(
                $path
            ) ?: 'application/octet-stream';

        return
            'data:'
            . $mime
            . ';base64,'
            . base64_encode(
                $contents
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Number
    |--------------------------------------------------------------------------
    */

    private function number(
        mixed $value,
        float $fallback,
    ): float {
        return is_numeric(
            $value
        )
            ? (float) $value
            : $fallback;
    }

    /*
    |--------------------------------------------------------------------------
    | Positive Number
    |--------------------------------------------------------------------------
    */

    private function positiveNumber(
        mixed $value,
        float $fallback,
    ): float {
        $number =
            $this->number(
                $value,
                $fallback
            );

        return
            $number > 0
                ? $number
                : $fallback;
    }
}