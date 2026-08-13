<?php

declare(strict_types=1);

namespace App\Presentation\Api\Participant\Controllers;

use App\Core\Organization\Models\Organization;
use App\Core\Participant\Actions\ImportParticipants;
use App\Core\Participant\Actions\PreviewParticipantImport;
use App\Core\Program\Models\Program;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Participant\Requests\CommitParticipantImportRequest;
use App\Presentation\Api\Participant\Requests\PreviewParticipantImportRequest;
use Illuminate\Http\JsonResponse;
use RuntimeException;

final class ParticipantImportController extends Controller
{
    public function __construct(
        private readonly PreviewParticipantImport $previewParticipantImport,
        private readonly ImportParticipants $importParticipants,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Preview Import
    |--------------------------------------------------------------------------
    |
    | Parses and validates the uploaded file.
    |
    | No participant or enrollment records are changed.
    |
    */

    public function preview(
        PreviewParticipantImportRequest $request,
    ): JsonResponse {
        try {
            $organization =
                $this->organization();

            $program =
                $this->findProgram(
                    organizationId:
                        $organization->id,

                    programUuid:
                        $request
                            ->string(
                                'program_uuid',
                            )
                            ->toString(),
                );

            $file =
                $request->file(
                    'file',
                );

            if (
                $file ===
                null
            ) {
                return response()->json(
                    [
                        'message' =>
                            'Import file is required.',
                    ],
                    422,
                );
            }

            $realPath =
                $file->getRealPath();

            if (
                $realPath ===
                false
            ) {
                return response()->json(
                    [
                        'message' =>
                            'Unable to read the uploaded import file.',
                    ],
                    422,
                );
            }

            $preview =
                $this->previewParticipantImport
                    ->execute(
                        filePath:
                            $realPath,

                        originalName:
                            $file
                                ->getClientOriginalName(),

                        organizationId:
                            $organization->id,
                    );

            return response()->json(
                [
                    'data' => [
                        'program' => [
                            'uuid' =>
                                $program->uuid,

                            'program_code' =>
                                $program->program_code,

                            'title' =>
                                $program->title,
                        ],

                        'file' => [
                            'name' =>
                                $file
                                    ->getClientOriginalName(),

                            'size' =>
                                $file
                                    ->getSize(),

                            'extension' =>
                                strtolower(
                                    $file
                                        ->getClientOriginalExtension(),
                                ),
                        ],

                        'summary' =>
                            $preview[
                                'summary'
                            ],

                        'rows' =>
                            $preview[
                                'rows'
                            ],
                    ],
                ],
                200,
            );
        } catch (
            RuntimeException $exception
        ) {
            return response()->json(
                [
                    'message' =>
                        $exception->getMessage(),
                ],
                422,
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Commit Import
    |--------------------------------------------------------------------------
    |
    | Creates or updates participants and automatically enrolls each
    | successful participant into the selected program.
    |
    */

    public function commit(
        CommitParticipantImportRequest $request,
    ): JsonResponse {
        try {
            $organization =
                $this->organization();

            $program =
                $this->findProgram(
                    organizationId:
                        $organization->id,

                    programUuid:
                        $request
                            ->string(
                                'program_uuid',
                            )
                            ->toString(),
                );

            $file =
                $request->file(
                    'file',
                );

            if (
                $file ===
                null
            ) {
                return response()->json(
                    [
                        'message' =>
                            'Import file is required.',
                    ],
                    422,
                );
            }

            $result =
                $this->importParticipants
                    ->execute(
                        file:
                            $file,

                        program:
                            $program,

                        updateExisting:
                            $request->boolean(
                                'update_existing',
                                true,
                            ),

                        enrollmentStatus:
                            $request->input(
                                'enrollment_status',
                                'enrolled',
                            ),
                    );

            return response()->json(
                [
                    'data' => [
                        'program' => [
                            'uuid' =>
                                $program->uuid,

                            'program_code' =>
                                $program->program_code,

                            'title' =>
                                $program->title,
                        ],

                        'file' => [
                            'name' =>
                                $file
                                    ->getClientOriginalName(),

                            'size' =>
                                $file
                                    ->getSize(),

                            'extension' =>
                                strtolower(
                                    $file
                                        ->getClientOriginalExtension(),
                                ),
                        ],

                        'batch_uuid' =>
                            $result[
                                'batch_uuid'
                            ],

                        'summary' =>
                            $result[
                                'summary'
                            ],

                        'rows' =>
                            $result[
                                'rows'
                            ],
                    ],
                ],
                201,
            );
        } catch (
            RuntimeException $exception
        ) {
            return response()->json(
                [
                    'message' =>
                        $exception->getMessage(),
                ],
                422,
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Active Organization
    |--------------------------------------------------------------------------
    */

    private function organization(): Organization
    {
        if (
            !app()->bound(
                'organization_uuid',
            )
        ) {
            throw new RuntimeException(
                'No organization is selected.',
            );
        }

        $organizationUuid =
            app(
                'organization_uuid',
            );

        if (
            !is_string(
                $organizationUuid,
            ) ||
            $organizationUuid ===
                ''
        ) {
            throw new RuntimeException(
                'No organization is selected.',
            );
        }

        $organization =
            Organization::query()
                ->where(
                    'uuid',
                    $organizationUuid,
                )
                ->first();

        if (
            $organization ===
            null
        ) {
            throw new RuntimeException(
                'Organization not found.',
            );
        }

        return $organization;
    }

    /*
    |--------------------------------------------------------------------------
    | Tenant Program
    |--------------------------------------------------------------------------
    */

    private function findProgram(
        int $organizationId,
        string $programUuid,
    ): Program {
        $program =
            Program::query()
                ->where(
                    'organization_id',
                    $organizationId,
                )
                ->where(
                    'uuid',
                    $programUuid,
                )
                ->first();

        if (
            $program ===
            null
        ) {
            throw new RuntimeException(
                'Program not found for this organization.',
            );
        }

        return $program;
    }
}