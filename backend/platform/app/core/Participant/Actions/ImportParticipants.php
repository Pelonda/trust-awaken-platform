<?php

declare(strict_types=1);

namespace App\Core\Participant\Actions;

use App\Core\Participant\Models\Participant;
use App\Core\Participant\Services\ParticipantImportParser;
use App\Core\Program\Models\Program;
use App\Core\ProgramEnrollment\Actions\EnrollParticipant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RuntimeException;

final class ImportParticipants
{
    public function __construct(
        private readonly ParticipantImportParser $parser,
        private readonly EnrollParticipant $enrollParticipant,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function execute(
        UploadedFile $file,
        Program $program,
        bool $updateExisting = true,
        string $enrollmentStatus = 'enrolled',
    ): array {
        $rows =
            $this->parser->parse(
                $file,
            );

        if ($rows === []) {
            throw new RuntimeException(
                'The import file does not contain any participant rows.'
            );
        }

        $batchUuid =
            (string) Str::uuid();

        $created = 0;
        $updated = 0;
        $enrolled = 0;
        $skipped = 0;

        $results = [];

        $seenCodes = [];
        $seenEmails = [];

        DB::transaction(
    function () use (
        $file,
        $rows,
        $program,
        $updateExisting,
        $enrollmentStatus,
        $batchUuid,
        &$created,
        &$updated,
        &$enrolled,
        &$skipped,
        &$results,
        &$seenCodes,
        &$seenEmails,
    ): void {
                foreach (
                    $rows as $index => $row
                ) {
                    $rowNumber =
                        $index + 2;

                    $data =
                        $this->normalizeRow(
                            $row,
                        );

                    $validator =
                        Validator::make(
                            $data,
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

                    if ($validator->fails()) {
                        $skipped++;

                        $results[] = [
                            'row' => $rowNumber,
                            'status' => 'invalid',
                            'errors' =>
                                $validator
                                    ->errors()
                                    ->all(),
                        ];

                        continue;
                    }

                    $codeKey =
                        strtolower(
                            (string)
                            $data[
                                'participant_code'
                            ],
                        );

                    $emailKey =
                        strtolower(
                            (string) (
                                $data['email']
                                ?? ''
                            ),
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Duplicate Rows
                    |--------------------------------------------------------------------------
                    */

                    if (
                        isset(
                            $seenCodes[
                                $codeKey
                            ],
                        )
                    ) {
                        $skipped++;

                        $results[] = [
                            'row' =>
                                $rowNumber,

                            'status' =>
                                'duplicate',

                            'errors' => [
                                'Duplicate participant_code in the import file.',
                            ],
                        ];

                        continue;
                    }

                    if (
                        $emailKey !== '' &&
                        isset(
                            $seenEmails[
                                $emailKey
                            ],
                        )
                    ) {
                        $skipped++;

                        $results[] = [
                            'row' =>
                                $rowNumber,

                            'status' =>
                                'duplicate',

                            'errors' => [
                                'Duplicate email in the import file.',
                            ],
                        ];

                        continue;
                    }

                    $seenCodes[
                        $codeKey
                    ] =
                        true;

                    if (
                        $emailKey !== ''
                    ) {
                        $seenEmails[
                            $emailKey
                        ] =
                            true;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Existing Participant
                    |--------------------------------------------------------------------------
                    */

                    $participant =
                        Participant::query()
                            ->where(
                                'organization_id',
                                $program
                                    ->organization_id,
                            )
                            ->whereRaw(
                                'LOWER(participant_code) = ?',
                                [
                                    $codeKey,
                                ],
                            )
                            ->first();

                    if (
                        $participant ===
                            null &&
                        $emailKey !== ''
                    ) {
                        $participant =
                            Participant::query()
                                ->where(
                                    'organization_id',
                                    $program
                                        ->organization_id,
                                )
                                ->whereRaw(
                                    'LOWER(email) = ?',
                                    [
                                        $emailKey,
                                    ],
                                )
                                ->first();
                    }

                    $wasExisting =
                        $participant !==
                        null;

                    /*
                    |--------------------------------------------------------------------------
                    | Create
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $participant ===
                        null
                    ) {
                        $participant =
                            Participant::query()
                                ->create([
                                    'uuid' =>
                                        (string)
                                        Str::uuid(),

                                    'organization_id' =>
                                        $program
                                            ->organization_id,

                                    'participant_code' =>
                                        $data[
                                            'participant_code'
                                        ],

                                    'first_name' =>
                                        $data[
                                            'first_name'
                                        ],

                                    'last_name' =>
                                        $data[
                                            'last_name'
                                        ],

                                    'email' =>
                                        $data[
                                            'email'
                                        ],

                                    'phone' =>
                                        $data[
                                            'phone'
                                        ],

                                    'date_of_birth' =>
                                        $data[
                                            'date_of_birth'
                                        ],

                                    'gender' =>
                                        $data[
                                            'gender'
                                        ],

                                    'country' =>
                                        $data[
                                            'country'
                                        ],

                                    'status' =>
                                        'active',
                                ]);

                        $created++;
                    } elseif (
                        $updateExisting
                    ) {
                        /*
                        |--------------------------------------------------------------------------
                        | Update Existing
                        |--------------------------------------------------------------------------
                        */

                        $participant->fill([
                            'first_name' =>
                                $data[
                                    'first_name'
                                ],

                            'last_name' =>
                                $data[
                                    'last_name'
                                ],

                            'email' =>
                                $data[
                                    'email'
                                ],

                            'phone' =>
                                $data[
                                    'phone'
                                ],

                            'date_of_birth' =>
                                $data[
                                    'date_of_birth'
                                ],

                            'gender' =>
                                $data[
                                    'gender'
                                ],

                            'country' =>
                                $data[
                                    'country'
                                ],
                        ]);

                        $participant->save();

                        $updated++;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Program Enrollment
                    |--------------------------------------------------------------------------
                    */

                    $this->enrollParticipant
                        ->execute(
                            program:
                                $program,

                            participant:
                                $participant,

                            status:
                                $enrollmentStatus,

                            metadata: [
                                'source' =>
                                    'participant_import',

                                'import_batch_uuid' =>
                                    $batchUuid,

                                'import_row' =>
                                    $rowNumber,

                                'original_filename' =>
                                    $file
                                        ->getClientOriginalName(),
                            ],
                        );

                    $enrolled++;

                    $results[] = [
                        'row' =>
                            $rowNumber,

                        'status' =>
                            $wasExisting
                                ? (
                                    $updateExisting
                                        ? 'updated'
                                        : 'existing'
                                )
                                : 'created',

                        'participant_uuid' =>
                            $participant->uuid,

                        'participant_code' =>
                            $participant
                                ->participant_code,

                        'name' =>
                            trim(
                                $participant
                                    ->first_name
                                . ' '
                                . $participant
                                    ->last_name,
                            ),

                        'enrolled' =>
                            true,
                    ];
                }
            },
        );

        return [
            'batch_uuid' =>
                $batchUuid,

            'summary' => [
                'total_rows' =>
                    count(
                        $rows,
                    ),

                'created' =>
                    $created,

                'updated' =>
                    $updated,

                'enrolled' =>
                    $enrolled,

                'skipped' =>
                    $skipped,
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

        return $value === ''
            ? null
            : $value;
    }
}