<?php

declare(strict_types=1);

namespace App\Presentation\Api\Credential\Controllers;

use App\Core\Credential\Actions\CreateCredential;
use App\Core\Credential\DTOs\CreateCredentialData;
use App\Core\Credential\Repositories\CredentialRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Credential\Requests\StoreCredentialRequest;
use App\Presentation\Api\Credential\Resources\CredentialResource;
use Illuminate\Http\JsonResponse;
use App\Core\Credential\Actions\UpdateCredential;
use App\Core\Credential\DTOs\UpdateCredentialData;
use App\Presentation\Api\Credential\Requests\UpdateCredentialRequest;
use RuntimeException;
use App\Core\Credential\Actions\RevokeCredential;
use App\Core\Credential\Actions\RestoreCredential;

final class CredentialController extends Controller
{
    public function __construct(
        private readonly CreateCredential $createCredential,
        private readonly UpdateCredential $updateCredential,
        private readonly CredentialRepositoryInterface $repository,
        private readonly RevokeCredential $revokeCredential,
        private readonly RestoreCredential $restoreCredential,
    ) {
    }

    public function index(): JsonResponse
    {
        return CredentialResource::collection(
            $this->repository->paginate()
        )->response();
    }

    public function show(string $uuid): JsonResponse
    {
        $credential = $this->repository->findByUuid($uuid);

        if ($credential === null) {
            return response()->json([
                'message' => 'Credential not found.',
            ], 404);
        }

        return (new CredentialResource($credential))
            ->response()
            ->setStatusCode(200);
    }

    public function store(
        StoreCredentialRequest $request,
    ): JsonResponse {
        $credential = $this->createCredential->execute(
            new CreateCredentialData(
                organizationId: $request->integer('organization_id'),
                participantId: $request->integer('participant_id'),
                programId: $request->integer('program_id'),
                sessionId: $request->integer('session_id'),

                templateId: $request->filled('template_id')
                    ? $request->integer('template_id')
                    : null,

                documentTemplateId: $request->filled('document_template_id')
                    ? $request->integer('document_template_id')
                    : null,

                credentialType: $request->string('credential_type')->toString(),
                expiresAt: $request->input('expires_at'),
                metadata: $request->input('metadata'),
            )
        );

        return (new CredentialResource($credential))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdateCredentialRequest $request,
        string $uuid,
    ): JsonResponse {
        try {
            $credential = $this->updateCredential->execute(
                uuid: $uuid,
                data: new UpdateCredentialData(
                    status: $request->string('status')->toString(),
                )
            );
        } catch (RuntimeException) {
            return response()->json([
                'message' => 'Credential not found.',
            ], 404);
        }

        return (new CredentialResource($credential))
            ->response()
            ->setStatusCode(200);
    }

    public function revoke(string $uuid): JsonResponse
    {
        try {
            $credential = $this->revokeCredential->execute($uuid);
        } catch (RuntimeException) {
            return response()->json([
                'message' => 'Credential not found.',
            ], 404);
        }

        return (new CredentialResource($credential))
            ->response()
            ->setStatusCode(200);
    }

    public function restore(string $uuid): JsonResponse
    {
        try {
            $credential = $this->restoreCredential->execute($uuid);
        } catch (RuntimeException) {
            return response()->json([
                'message' => 'Credential not found.',
            ], 404);
        }

        return (new CredentialResource($credential))
            ->response()
            ->setStatusCode(200);
    }
}