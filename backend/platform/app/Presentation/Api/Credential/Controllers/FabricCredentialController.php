<?php

declare(strict_types=1);

namespace App\Presentation\Api\Credential\Controllers;

use App\Core\Credential\Actions\IssueFabricCredential;
use App\Core\Credential\DTOs\CreateCredentialData;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Credential\Requests\IssueFabricCredentialRequest;
use App\Presentation\Api\Credential\Resources\CredentialResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

final class FabricCredentialController extends Controller
{
    public function __construct(
        private readonly IssueFabricCredential $issueFabricCredential,
    ) {
    }

    public function store(
        IssueFabricCredentialRequest $request
    ): JsonResponse {
        try {
            /*
            |--------------------------------------------------------------------------
            | Selected Organization
            |--------------------------------------------------------------------------
            |
            | The browser does not send an internal organization ID.
            |
            | The selected organization UUID comes from:
            |
            | X-Organization
            |
            */

            $organization =
                $this->organization(
                    $request
                );

            /*
            |--------------------------------------------------------------------------
            | Participant
            |--------------------------------------------------------------------------
            |
            | Participant must belong directly
            | to the selected organization.
            |
            */

            $participant =
                DB::table(
                    'participants'
                )
                    ->where(
                        'uuid',
                        $request->string(
                            'participant_uuid'
                        )->toString()
                    )
                    ->where(
                        'organization_id',
                        $organization->id
                    )
                    ->whereNull(
                        'deleted_at'
                    )
                    ->first([
                        'id',
                        'uuid',
                    ]);

            if (!$participant) {
                throw new RuntimeException(
                    'Participant was not found in the selected organization.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Program
            |--------------------------------------------------------------------------
            */

            $program =
                DB::table(
                    'programs'
                )
                    ->where(
                        'uuid',
                        $request->string(
                            'program_uuid'
                        )->toString()
                    )
                    ->where(
                        'organization_id',
                        $organization->id
                    )
                    ->whereNull(
                        'deleted_at'
                    )
                    ->first([
                        'id',
                        'uuid',
                        'credential_enabled',
                    ]);

            if (!$program) {
                throw new RuntimeException(
                    'Program was not found in the selected organization.'
                );
            }

            if (
                !((bool)
                    $program
                        ->credential_enabled)
            ) {
                throw new RuntimeException(
                    'Credential issuance is disabled for this program.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Session
            |--------------------------------------------------------------------------
            |
            | Sessions do not contain organization_id.
            |
            | Their tenant relationship is:
            |
            | organization
            |      ↓
            | program
            |      ↓
            | session
            |
            */

            $session =
                DB::table(
                    'program_sessions'
                )
                    ->where(
                        'uuid',
                        $request->string(
                            'session_uuid'
                        )->toString()
                    )
                    ->where(
                        'program_id',
                        $program->id
                    )
                    ->whereNull(
                        'deleted_at'
                    )
                    ->first([
                        'id',
                        'uuid',
                    ]);

            if (!$session) {
                throw new RuntimeException(
                    'Session was not found for the selected program.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Fabric Document Template
            |--------------------------------------------------------------------------
            */

            $documentTemplate =
                DB::table(
                    'document_templates'
                )
                    ->where(
                        'uuid',
                        $request->string(
                            'document_template_uuid'
                        )->toString()
                    )
                    ->where(
                        'organization_id',
                        $organization->id
                    )
                    ->whereNull(
                        'deleted_at'
                    )
                    ->first([
                        'id',
                        'uuid',
                    ]);

            if (!$documentTemplate) {
                throw new RuntimeException(
                    'Fabric document template was not found in the selected organization.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Issue Credential
            |--------------------------------------------------------------------------
            |
            | Internal numeric IDs are resolved
            | here at the trusted backend boundary.
            |
            */

            $credential =
                $this->issueFabricCredential
                    ->execute(
                        new CreateCredentialData(
                            organizationId:
                                (int)
                                $organization->id,

                            participantId:
                                (int)
                                $participant->id,

                            programId:
                                (int)
                                $program->id,

                            sessionId:
                                (int)
                                $session->id,

                            /*
                             * Fabric issuance never
                             * uses the legacy template.
                             */
                            templateId:
                                null,

                            documentTemplateId:
                                (int)
                                $documentTemplate->id,

                            credentialType:
                                $request
                                    ->string(
                                        'credential_type'
                                    )
                                    ->toString(),

                            expiresAt:
                                $request->input(
                                    'expires_at'
                                ),

                            metadata:
                                $request->input(
                                    'metadata'
                                ),
                        )
                    );

            return (
                new CredentialResource(
                    $credential
                )
            )
                ->response()
                ->setStatusCode(
                    201
                );

        } catch (
            RuntimeException $exception
        ) {
            return response()->json(
                [
                    'message' =>
                        $exception
                            ->getMessage(),
                ],
                422
            );

        } catch (
            Throwable $exception
        ) {
            report(
                $exception
            );

            return response()->json(
                [
                    'message' =>
                        'Unable to issue Fabric credential.',
                ],
                500
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Selected Organization
    |--------------------------------------------------------------------------
    */

    private function organization(
        IssueFabricCredentialRequest $request
    ): object {
        $organizationUuid =
            $request->header(
                'X-Organization'
            );

        if (
            !$organizationUuid
        ) {
            throw new RuntimeException(
                'Organization is required.'
            );
        }

        $organization =
            DB::table(
                'organizations'
            )
                ->where(
                    'uuid',
                    $organizationUuid
                )
                ->where(
                    'status',
                    'active'
                )
                ->whereNull(
                    'deleted_at'
                )
                ->first([
                    'id',
                    'uuid',
                    'display_name',
                ]);

        if (!$organization) {
            throw new RuntimeException(
                'Selected organization was not found or is inactive.'
            );
        }

        return $organization;
    }
}