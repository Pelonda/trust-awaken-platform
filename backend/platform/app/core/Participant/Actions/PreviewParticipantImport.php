<?php

declare(strict_types=1);

namespace App\Core\Participant\Actions;

use App\Core\Participant\Models\Participant;
use App\Core\Participant\Services\ParticipantImportParser;
use Illuminate\Support\Facades\Validator;

final class PreviewParticipantImport
{
    public function __construct(
        private readonly ParticipantImportParser $parser,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function execute(
        string $filePath,
        string $originalName,
        int $organizationId,
    ): array {
        $file =
            new \Illuminate\Http\UploadedFile(
                $filePath,
                $originalName,
                null,
                null,
                true,
            );

        $rows =
            $this->parser->parse(
                $file,
            );

        $results = [];

        $validRows = 0;
        $invalidRows = 0;
        $newParticipants = 0;
        $existingParticipants = 0;
        $duplicates = 0;

        /*
        |--------------------------------------------------------------------------
        | Duplicate Detection
        |--------------------------------------------------------------------------
        */

        $seenCodes = [];
        $seenEmails = [];

        foreach (
            $rows as $index => $row
        ) {
            $rowNumber =
                $index + 2;

            $normalized =
                $this->normalizeRow(
                    $row,
                );

            $validator =
                Validator::make(
                    $normalized,
                    [
                        'participant_code' => [
                            'required',
                            'string',
                            'max:100',
                        ],

                        'first_name' => [
                            'required',
                            'string',
                            'max:255',
                        ],

                        'last_name' => [
                            'required',
                            'string',
                            'max:255',
                        ],

                        'email' => [
                            'nullable',
                            'email',
                            'max:255',
                        ],

                        'phone' => [
                            'nullable',
                            'string',
                            'max:100',
                        ],

                        'date_of_birth' => [
                            'nullable',
                            'date',
                        ],

                        'gender' => [
                            'nullable',
                            'string',
                            'max:100',
                        ],

                        'country' => [
                            'nullable',
                            'string',
                            'max:255',
                        ],
                    ],
                );

            $errors =
                $validator
                    ->errors()
                    ->all();

            $codeKey =
                strtolower(
                    trim(
                        (string) (
                            $normalized[
                                'participant_code'
                            ] ?? ''
                        ),
                    ),
                );

            $emailKey =
                strtolower(
                    trim(
                        (string) (
                            $normalized[
                                'email'
                            ] ?? ''
                        ),
                    ),
                );

            $duplicate =
                false;

            if (
                $codeKey !== ''
            ) {
                if (
                    isset(
                        $seenCodes[
                            $codeKey
                        ],
                    )
                ) {
                    $duplicate =
                        true;

                    $errors[] =
                        'Duplicate participant_code in the import file.';
                } else {
                    $seenCodes[
                        $codeKey
                    ] =
                        $rowNumber;
                }
            }

            if (
                $emailKey !== ''
            ) {
                if (
                    isset(
                        $seenEmails[
                            $emailKey
                        ],
                    )
                ) {
                    $duplicate =
                        true;

                    $errors[] =
                        'Duplicate email in the import file.';
                } else {
                    $seenEmails[
                        $emailKey
                    ] =
                        $rowNumber;
                }
            }

            if (
                $duplicate
            ) {
                $duplicates++;
            }

            /*
            |--------------------------------------------------------------------------
            | Existing Participant
            |--------------------------------------------------------------------------
            |
            | Match participant_code first.
            | Email is the secondary match.
            |
            */

            $existing =
                null;

            if (
                $codeKey !== ''
            ) {
                $existing =
                    Participant::query()
                        ->where(
                            'organization_id',
                            $organizationId,
                        )
                        ->whereRaw(
                            'LOWER(participant_code) = ?',
                            [
                                $codeKey,
                            ],
                        )
                        ->first();
            }

            if (
                $existing ===
                    null &&
                $emailKey !== ''
            ) {
                $existing =
                    Participant::query()
                        ->where(
                            'organization_id',
                            $organizationId,
                        )
                        ->whereRaw(
                            'LOWER(email) = ?',
                            [
                                $emailKey,
                            ],
                        )
                        ->first();
            }

            $valid =
                $errors === [];

            if (
                $valid
            ) {
                $validRows++;

                if (
                    $existing
                ) {
                    $existingParticipants++;
                } else {
                    $newParticipants++;
                }
            } else {
                $invalidRows++;
            }

            $results[] = [
                'row' =>
                    $rowNumber,

                'valid' =>
                    $valid,

                'duplicate' =>
                    $duplicate,

                'existing' =>
                    $existing !==
                    null,

                'existing_participant_uuid' =>
                    $existing?->uuid,

                'data' =>
                    $normalized,

                'errors' =>
                    $errors,
            ];
        }

        return [
            'summary' => [
                'total_rows' =>
                    count(
                        $rows,
                    ),

                'valid_rows' =>
                    $validRows,

                'invalid_rows' =>
                    $invalidRows,

                'new_participants' =>
                    $newParticipants,

                'existing_participants' =>
                    $existingParticipants,

                'duplicates' =>
                    $duplicates,
            ],

            'rows' =>
                $results,
        ];
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    private function normalizeRow(
        array $row,
    ): array {
        return [
            'participant_code' =>
                $this->nullableString(
                    $row[
                        'participant_code'
                    ] ?? null,
                ),

            'first_name' =>
                $this->nullableString(
                    $row[
                        'first_name'
                    ] ?? null,
                ),

            'last_name' =>
                $this->nullableString(
                    $row[
                        'last_name'
                    ] ?? null,
                ),

            'email' =>
                $this->nullableString(
                    $row[
                        'email'
                    ] ?? null,
                ),

            'phone' =>
                $this->nullableString(
                    $row[
                        'phone'
                    ] ?? null,
                ),

            'date_of_birth' =>
                $this->nullableString(
                    $row[
                        'date_of_birth'
                    ] ?? null,
                ),

            'gender' =>
                $this->nullableString(
                    $row[
                        'gender'
                    ] ?? null,
                ),

            'country' =>
                $this->nullableString(
                    $row[
                        'country'
                    ] ?? null,
                ),
        ];
    }

    private function nullableString(
        mixed $value,
    ): ?string {
        if (
            $value ===
            null
        ) {
            return null;
        }

        $value =
            trim(
                (string) $value,
            );

        return $value ===
            ''
            ? null
            : $value;
    }
}