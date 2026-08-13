<?php

declare(strict_types=1);

namespace App\Presentation\Api\ProgramEnrollment\Controllers;

use App\Core\Organization\Models\Organization;
use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use App\Core\ProgramEnrollment\Actions\BulkEnrollParticipants;
use App\Core\ProgramEnrollment\Actions\EnrollParticipant;
use App\Core\ProgramEnrollment\Actions\UpdateEnrollmentStatus;
use App\Core\ProgramEnrollment\Models\ProgramEnrollment;
use App\Http\Controllers\Controller;
use App\Presentation\Api\ProgramEnrollment\Requests\BulkProgramEnrollmentRequest;
use App\Presentation\Api\ProgramEnrollment\Requests\StoreProgramEnrollmentRequest;
use App\Presentation\Api\ProgramEnrollment\Requests\UpdateProgramEnrollmentRequest;
use App\Presentation\Api\ProgramEnrollment\Resources\ProgramEnrollmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;

final class ProgramEnrollmentController extends Controller
{
    public function __construct(
        private readonly EnrollParticipant $enrollParticipant,
        private readonly BulkEnrollParticipants $bulkEnrollParticipants,
        private readonly UpdateEnrollmentStatus $updateEnrollmentStatus,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | List Program Enrollments
    |--------------------------------------------------------------------------
    */

    public function index(
        string $programUuid,
    ): AnonymousResourceCollection {
        $organization =
            $this->organization();

        $program =
            $this->findProgram(
                $programUuid,
                $organization->id,
            );

        $enrollments =
            ProgramEnrollment::query()
                ->where(
                    'organization_id',
                    $organization->id,
                )
                ->where(
                    'program_id',
                    $program->id,
                )
                ->with([
                    'participant',
                    'program',
                ])
                ->orderByDesc(
                    'enrolled_at',
                )
                ->get();

        return ProgramEnrollmentResource::collection(
            $enrollments,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Enroll One Participant
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreProgramEnrollmentRequest $request,
        string $programUuid,
    ): JsonResponse {
        try {
            $organization =
                $this->organization();

            $program =
                $this->findProgram(
                    $programUuid,
                    $organization->id,
                );

            $participant =
                Participant::query()
                    ->where(
                        'organization_id',
                        $organization->id,
                    )
                    ->where(
                        'uuid',
                        $request
                            ->string(
                                'participant_uuid',
                            )
                            ->toString(),
                    )
                    ->first();

            if (
                $participant ===
                null
            ) {
                return response()->json(
                    [
                        'message' =>
                            'Participant not found for this organization.',
                    ],
                    404,
                );
            }

            $enrollment =
                $this->enrollParticipant
                    ->execute(
                        program:
                            $program,

                        participant:
                            $participant,

                        status:
                            $request->input(
                                'status',
                                'enrolled',
                            ),

                        metadata:
                            $request->input(
                                'metadata',
                            ),
                    );

            $enrollment->load([
                'participant',
                'program',
            ]);

            return (
                new ProgramEnrollmentResource(
                    $enrollment,
                )
            )
                ->response()
                ->setStatusCode(
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
    | Bulk Enroll Participants
    |--------------------------------------------------------------------------
    */

    public function bulk(
        BulkProgramEnrollmentRequest $request,
        string $programUuid,
    ): JsonResponse {
        try {
            $organization =
                $this->organization();

            $program =
                $this->findProgram(
                    $programUuid,
                    $organization->id,
                );

            $requestedUuids =
                array_values(
                    array_unique(
                        $request->input(
                            'participant_uuids',
                            [],
                        ),
                    ),
                );

            /*
             * Resolve every requested participant
             * inside the active tenant.
             */

            $participants =
                Participant::query()
                    ->where(
                        'organization_id',
                        $organization->id,
                    )
                    ->whereIn(
                        'uuid',
                        $requestedUuids,
                    )
                    ->get([
                        'uuid',
                    ]);

            $foundUuids =
                $participants
                    ->pluck(
                        'uuid',
                    )
                    ->all();

            $missingUuids =
                array_values(
                    array_diff(
                        $requestedUuids,
                        $foundUuids,
                    ),
                );

            /*
             * Fail atomically if any requested
             * participant is invalid.
             */

            if (
                $missingUuids !==
                []
            ) {
                return response()->json(
                    [
                        'message' =>
                            'One or more participants were not found for this organization.',

                        'missing_participant_uuids' =>
                            $missingUuids,
                    ],
                    422,
                );
            }

            $enrollments =
                $this->bulkEnrollParticipants
                    ->execute(
                        program:
                            $program,

                        participantUuids:
                            $requestedUuids,

                        status:
                            $request->input(
                                'status',
                                'enrolled',
                            ),

                        metadata:
                            $request->input(
                                'metadata',
                            ),
                    );

            $enrollments->each(
                function (
                    ProgramEnrollment $enrollment,
                ): void {
                    $enrollment->load([
                        'participant',
                        'program',
                    ]);
                },
            );

            return response()->json(
                [
                    'data' =>
                        ProgramEnrollmentResource::collection(
                            $enrollments,
                        )->resolve(),

                    'summary' => [
                        'requested' =>
                            count(
                                $requestedUuids,
                            ),

                        'enrolled' =>
                            $enrollments->count(),

                        'missing' =>
                            0,
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
    | Update Enrollment Status
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateProgramEnrollmentRequest $request,
        string $programUuid,
        string $enrollmentUuid,
    ): JsonResponse {
        try {
            $organization =
                $this->organization();

            $program =
                $this->findProgram(
                    $programUuid,
                    $organization->id,
                );

            $enrollment =
                $this->findEnrollment(
                    organizationId:
                        $organization->id,

                    programId:
                        $program->id,

                    enrollmentUuid:
                        $enrollmentUuid,
                );

            $enrollment =
                $this->updateEnrollmentStatus
                    ->execute(
                        enrollment:
                            $enrollment,

                        status:
                            $request
                                ->string(
                                    'status',
                                )
                                ->toString(),
                    );

            return (
                new ProgramEnrollmentResource(
                    $enrollment,
                )
            )
                ->response()
                ->setStatusCode(
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
                404,
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Enrollment
    |--------------------------------------------------------------------------
    */

    public function destroy(
        string $programUuid,
        string $enrollmentUuid,
    ): JsonResponse {
        try {
            $organization =
                $this->organization();

            $program =
                $this->findProgram(
                    $programUuid,
                    $organization->id,
                );

            $enrollment =
                $this->findEnrollment(
                    organizationId:
                        $organization->id,

                    programId:
                        $program->id,

                    enrollmentUuid:
                        $enrollmentUuid,
                );

            $enrollment->delete();

            return response()->json(
                [],
                204,
            );
        } catch (
            RuntimeException $exception
        ) {
            return response()->json(
                [
                    'message' =>
                        $exception->getMessage(),
                ],
                404,
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Active Organization
    |--------------------------------------------------------------------------
    |
    | OrganizationMiddleware places X-Organization into the container as
    | "organization_uuid".
    |
    | We resolve that UUID to the actual tenant here and use its numeric ID
    | for every enrollment query.
    |
    */

    private function organization():
        Organization {
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
        string $uuid,
        int $organizationId,
    ): Program {
        $program =
            Program::query()
                ->where(
                    'organization_id',
                    $organizationId,
                )
                ->where(
                    'uuid',
                    $uuid,
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

    /*
    |--------------------------------------------------------------------------
    | Tenant Enrollment
    |--------------------------------------------------------------------------
    */

    private function findEnrollment(
        int $organizationId,
        int $programId,
        string $enrollmentUuid,
    ): ProgramEnrollment {
        $enrollment =
            ProgramEnrollment::query()
                ->where(
                    'organization_id',
                    $organizationId,
                )
                ->where(
                    'program_id',
                    $programId,
                )
                ->where(
                    'uuid',
                    $enrollmentUuid,
                )
                ->first();

        if (
            $enrollment ===
            null
        ) {
            throw new RuntimeException(
                'Program enrollment not found.',
            );
        }

        return $enrollment;
    }
}