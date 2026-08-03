<?php

declare(strict_types=1);

namespace App\Presentation\Api\CredentialTemplate\Controllers;

use App\Core\CredentialTemplate\Actions\CreateCredentialTemplate;
use App\Core\CredentialTemplate\DTOs\CreateCredentialTemplateData;
use App\Core\CredentialTemplate\Repositories\CredentialTemplateRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Api\CredentialTemplate\Requests\StoreCredentialTemplateRequest;
use App\Presentation\Api\CredentialTemplate\Resources\CredentialTemplateResource;
use Illuminate\Http\JsonResponse;
use App\Core\CredentialTemplate\Actions\UpdateCredentialTemplate;
use App\Core\CredentialTemplate\DTOs\UpdateCredentialTemplateData;
use App\Presentation\Api\CredentialTemplate\Requests\UpdateCredentialTemplateRequest;
use RuntimeException;
use App\Core\CredentialTemplate\Actions\ArchiveCredentialTemplate;
use App\Core\CredentialTemplate\Actions\RestoreCredentialTemplate;

final class CredentialTemplateController extends Controller
{
    public function __construct(
        private readonly CreateCredentialTemplate $createCredentialTemplate,
        private readonly CredentialTemplateRepositoryInterface $repository,
        private readonly UpdateCredentialTemplate $updateCredentialTemplate,
        private readonly ArchiveCredentialTemplate $archiveCredentialTemplate,
        private readonly RestoreCredentialTemplate $restoreCredentialTemplate,
    ) {
    }

    public function index(): JsonResponse
    {
        return CredentialTemplateResource::collection(
            $this->repository->paginate()
        )->response();
    }

    public function show(string $uuid): JsonResponse
    {
        $template = $this->repository->findByUuid($uuid);

        if ($template === null) {
            return response()->json([
                'message' => 'Credential template not found.',
            ], 404);
        }

        return (new CredentialTemplateResource($template))
            ->response()
            ->setStatusCode(200);
    }

    public function store(
        StoreCredentialTemplateRequest $request,
    ): JsonResponse {

        $template = $this->createCredentialTemplate->execute(
            new CreateCredentialTemplateData(
                organizationId: $request->integer('organization_id'),
                templateCode: $request->string('template_code')->toString(),
                name: $request->string('name')->toString(),
                credentialType: $request->string('credential_type')->toString(),
                paperSize: $request->string('paper_size')->toString(),
                orientation: $request->string('orientation')->toString(),
                backgroundImage: $request->input('background_image'),
                elements: $request->input('elements'),
                isDefault: $request->boolean('is_default'),
            )
        );

        return (new CredentialTemplateResource($template))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
    UpdateCredentialTemplateRequest $request,
    string $uuid,
): JsonResponse {

    try {

        $template = $this->updateCredentialTemplate->execute(
            uuid: $uuid,
            data: new UpdateCredentialTemplateData(
                name: $request->string('name')->toString(),
                paperSize: $request->string('paper_size')->toString(),
                orientation: $request->string('orientation')->toString(),
                isDefault: $request->boolean('is_default'),
            )
        );

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Credential template not found.',
        ], 404);

    }

    return (new CredentialTemplateResource($template))
        ->response()
        ->setStatusCode(200);
}

public function destroy(string $uuid): JsonResponse
{
    try {

        $this->archiveCredentialTemplate->execute($uuid);

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Credential template not found.',
        ], 404);

    }

    return response()->json([], 204);
}

public function restore(string $uuid): JsonResponse
{
    try {

        $template = $this->restoreCredentialTemplate->execute($uuid);

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Credential template not found.',
        ], 404);

    }

    return (new CredentialTemplateResource($template))
        ->response()
        ->setStatusCode(200);
}

}