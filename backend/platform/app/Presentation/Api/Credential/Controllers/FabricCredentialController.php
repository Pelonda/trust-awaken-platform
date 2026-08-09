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
            $organizationId =
                $this->organizationId(
                    $request
                );

            $credential =
                $this->issueFabricCredential
                    ->execute(
                        new CreateCredentialData(
                            organizationId:
                                $organizationId,

                            participantId:
                                $request->integer(
                                    'participant_id'
                                ),

                            programId:
                                $request->integer(
                                    'program_id'
                                ),

                            sessionId:
                                $request->integer(
                                    'session_id'
                                ),

                            templateId:
                                null,

                            documentTemplateId:
                                $request->integer(
                                    'document_template_id'
                                ),

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
        } catch (
            RuntimeException $exception
        ) {
            return response()->json([
                'message' =>
                    $exception->getMessage(),
            ], 422);
        } catch (
            Throwable $exception
        ) {
            report(
                $exception
            );

            return response()->json([
                'message' =>
                    'Unable to issue Fabric credential.',
            ], 500);
        }

        return (
            new CredentialResource(
                $credential
            )
        )
            ->response()
            ->setStatusCode(
                201
            );
    }

    private function organizationId(
        IssueFabricCredentialRequest $request
    ): int {
        $organizationUuid =
            $request->header(
                'X-Organization'
            );

        if (!$organizationUuid) {
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
                ]);

        if (!$organization) {
            throw new RuntimeException(
                'Organization not found or inactive.'
            );
        }

        return (int)
            $organization->id;
    }
}