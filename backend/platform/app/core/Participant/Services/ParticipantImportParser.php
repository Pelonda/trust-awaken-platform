<?php

declare(strict_types=1);

namespace App\Core\Participant\Services;

use Illuminate\Http\UploadedFile;
use RuntimeException;

final class ParticipantImportParser
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function parse(
        UploadedFile $file,
    ): array {
        $extension =
            strtolower(
                $file->getClientOriginalExtension()
            );

        return match ($extension) {
            'csv',
            'txt' => $this->parseCsv(
                $file
            ),

            'xlsx',
            'xls' => $this->parseSpreadsheet(
                $file
            ),

            default => throw new RuntimeException(
                'Unsupported import file. Please upload CSV, XLSX, or XLS.'
            ),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | CSV Parser
    |--------------------------------------------------------------------------
    */

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseCsv(
        UploadedFile $file,
    ): array {
        $path =
            $file->getRealPath();

        if (
            $path === false
        ) {
            throw new RuntimeException(
                'Unable to read the uploaded file.'
            );
        }

        $handle =
            fopen(
                $path,
                'rb'
            );

        if (
            $handle === false
        ) {
            throw new RuntimeException(
                'Unable to open the uploaded CSV file.'
            );
        }

        try {
            $headers =
                fgetcsv(
                    $handle
                );

            if (
                !is_array(
                    $headers
                )
            ) {
                throw new RuntimeException(
                    'The uploaded CSV file is empty.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Normalize CSV Headers
            |--------------------------------------------------------------------------
            */

            $headers =
                array_map(
                    fn (
                        mixed $header
                    ): string =>
                        $this->normalizeHeader(
                            $header
                        ),
                    $headers
                );

            $this->validateHeaders(
                $headers
            );

            $rows = [];

            while (
                (
                    $row =
                        fgetcsv(
                            $handle
                        )
                ) !== false
            ) {
                if (
                    $this->rowIsEmpty(
                        $row
                    )
                ) {
                    continue;
                }

                $rows[] =
                    $this->combine(
                        $headers,
                        $row
                    );
            }

            return $rows;
        } finally {
            fclose(
                $handle
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Excel Parser
    |--------------------------------------------------------------------------
    */

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseSpreadsheet(
        UploadedFile $file,
    ): array {
        if (
            !class_exists(
                \PhpOffice\PhpSpreadsheet\IOFactory::class
            )
        ) {
            throw new RuntimeException(
                'Excel support is not installed. Install phpoffice/phpspreadsheet first.'
            );
        }

        $path =
            $file->getRealPath();

        if (
            $path === false
        ) {
            throw new RuntimeException(
                'Unable to read the uploaded spreadsheet.'
            );
        }

        try {
            $spreadsheet =
                \PhpOffice\PhpSpreadsheet\IOFactory::load(
                    $path
                );

            $sheet =
                $spreadsheet
                    ->getActiveSheet();

            $rows =
                $sheet
                    ->toArray(
                        null,
                        true,
                        true,
                        false
                    );

            if (
                $rows === []
            ) {
                throw new RuntimeException(
                    'The uploaded spreadsheet is empty.'
                );
            }

            $headerRow =
                array_shift(
                    $rows
                );

            if (
                !is_array(
                    $headerRow
                )
            ) {
                throw new RuntimeException(
                    'The uploaded spreadsheet does not contain a valid header row.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Normalize Excel Headers
            |--------------------------------------------------------------------------
            */

            $headers =
                array_map(
                    fn (
                        mixed $header
                    ): string =>
                        $this->normalizeHeader(
                            $header
                        ),
                    $headerRow
                );

            $this->validateHeaders(
                $headers
            );

            $result = [];

            foreach (
                $rows as $row
            ) {
                if (
                    !is_array(
                        $row
                    )
                ) {
                    continue;
                }

                if (
                    $this->rowIsEmpty(
                        $row
                    )
                ) {
                    continue;
                }

                $result[] =
                    $this->combine(
                        $headers,
                        $row
                    );
            }

            return $result;
        } finally {
            if (
                isset(
                    $spreadsheet
                )
            ) {
                $spreadsheet
                    ->disconnectWorksheets();

                unset(
                    $spreadsheet
                );
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize Header
    |--------------------------------------------------------------------------
    |
    | Accepted examples:
    |
    | participant_code
    | participant\_code
    | Participant Code
    | participant-code
    | PARTICIPANT CODE
    |
    | All become:
    |
    | participant_code
    |
    */

    private function normalizeHeader(
        mixed $header,
    ): string {
        $header =
            trim(
                (string) $header
            );

        /*
         * Remove UTF-8 BOM.
         *
         * This commonly appears on the
         * first header of Windows/Excel
         * generated CSV files.
         */

        $header =
            preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                $header
            ) ?? $header;

        /*
         * Remove Unicode BOM when PHP has
         * already decoded the value.
         */

        $header =
            ltrim(
                $header,
                "\xEF\xBB\xBF"
            );

        /*
         * Handle escaped underscores:
         *
         * participant\_code
         *
         * becomes:
         *
         * participant_code
         */

        $header =
            str_replace(
                [
                    '\\_',
                    '\_',
                ],
                '_',
                $header
            );

        /*
         * Normalize case.
         */

        $header =
            strtolower(
                $header
            );

        /*
         * Spaces and hyphens become
         * underscores.
         */

        $header =
            preg_replace(
                '/[\s\-]+/',
                '_',
                $header
            ) ?? $header;

        /*
         * Remove characters that should
         * never be part of a field name.
         */

        $header =
            preg_replace(
                '/[^a-z0-9_]/',
                '',
                $header
            ) ?? $header;

        /*
         * Collapse repeated underscores.
         */

        $header =
            preg_replace(
                '/_+/',
                '_',
                $header
            ) ?? $header;

        return trim(
            $header,
            '_'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Required Headers
    |--------------------------------------------------------------------------
    |
    | Only the three identity fields are
    | required at the file level.
    |
    | Other participant fields are optional.
    |
    */

    /**
     * @param array<int, string> $headers
     */
    private function validateHeaders(
        array $headers,
    ): void {
        $required = [
            'participant_code',
            'first_name',
            'last_name',
        ];

        $missing =
            array_values(
                array_diff(
                    $required,
                    $headers
                )
            );

        if (
            $missing !== []
        ) {
            throw new RuntimeException(
                'Missing required import columns: '
                . implode(
                    ', ',
                    $missing
                )
                . '.'
            );
        }

        /*
         * Detect duplicate normalized
         * column names.
         *
         * Example:
         *
         * Participant Code
         * participant_code
         *
         * would both normalize to the same
         * key and should not be accepted.
         */

        $nonEmptyHeaders =
            array_values(
                array_filter(
                    $headers,
                    static fn (
                        string $header
                    ): bool =>
                        $header !== ''
                )
            );

        if (
            count(
                $nonEmptyHeaders
            ) !==
            count(
                array_unique(
                    $nonEmptyHeaders
                )
            )
        ) {
            throw new RuntimeException(
                'The import file contains duplicate column names.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Combine Headers + Row
    |--------------------------------------------------------------------------
    */

    /**
     * @param array<int, string> $headers
     * @param array<int, mixed> $row
     *
     * @return array<string, mixed>
     */
    private function combine(
        array $headers,
        array $row,
    ): array {
        $result = [];

        foreach (
            $headers as $index => $header
        ) {
            if (
                $header === ''
            ) {
                continue;
            }

            $value =
                $row[
                    $index
                ] ?? null;

            $result[
                $header
            ] =
                $this->normalizeValue(
                    $value
                );
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize Cell Value
    |--------------------------------------------------------------------------
    */

    private function normalizeValue(
        mixed $value,
    ): mixed {
        if (
            $value === null
        ) {
            return null;
        }

        if (
            is_string(
                $value
            )
        ) {
            $value =
                trim(
                    $value
                );

            return $value === ''
                ? null
                : $value;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Empty Row Detection
    |--------------------------------------------------------------------------
    */

    /**
     * @param array<int, mixed> $row
     */
    private function rowIsEmpty(
        array $row,
    ): bool {
        foreach (
            $row as $value
        ) {
            if (
                $value === null
            ) {
                continue;
            }

            if (
                trim(
                    (string) $value
                ) !== ''
            ) {
                return false;
            }
        }

        return true;
    }
}