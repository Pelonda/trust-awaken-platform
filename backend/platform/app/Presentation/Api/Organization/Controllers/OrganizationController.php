<?php

declare(strict_types=1);

namespace App\Presentation\Api\Organization\Controllers;

use App\Core\Organization\Actions\ArchiveOrganization;
use App\Core\Organization\Actions\CreateOrganization;
use App\Core\Organization\Actions\UpdateOrganization;
use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Organization\DTOs\UpdateOrganizationData;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Organization\Requests\StoreOrganizationRequest;
use App\Presentation\Api\Organization\Requests\UpdateOrganizationRequest;
use App\Presentation\Api\Organization\Resources\OrganizationResource;
use Illuminate\Http\JsonResponse;
use RuntimeException;
use App\Core\Organization\Actions\RestoreOrganization;

final class OrganizationController extends Controller
{
    /**
     * Temporary owner until the Identity module is implemented.
     */
    private const TEMP_OWNER_ID = 1;

    public function __construct(
        private readonly CreateOrganization $createOrganization,
        private readonly UpdateOrganization $updateOrganization,
        private readonly ArchiveOrganization $archiveOrganization,
        private readonly OrganizationRepositoryInterface $organizations,
        private readonly RestoreOrganization $restoreOrganization,
    ) {
    }

    public function index(): JsonResponse
    {
        return OrganizationResource::collection(
            $this->organizations->paginate()
        )->response();
    }

    public function show(string $uuid): JsonResponse
    {
        $organization = $this->organizations->findByUuid($uuid);

        if ($organization === null) {
            return response()->json([
                'message' => 'Organization not found.',
            ], 404);
        }

        return (new OrganizationResource($organization))
            ->response()
            ->setStatusCode(200);
    }

    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $organization = $this->createOrganization->execute(
    data: new CreateOrganizationData(
        displayName: $request->string('display_name')->toString(),
        legalName: $request->string('legal_name')->toString(),
        organizationType: $request->string('organization_type')->toString(),
    ),
    ownerUserId: (int) $request->input('owner_user_id'),
);

        return (new OrganizationResource($organization))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdateOrganizationRequest $request,
        string $uuid,
    ): JsonResponse {
        try {
            $organization = $this->updateOrganization->execute(
                uuid: $uuid,
                data: new UpdateOrganizationData(
                    displayName: $request->string('display_name')->toString(),
                    legalName: $request->string('legal_name')->toString(),
                    organizationType: $request->string('organization_type')->toString(),
                ),
            );
        } catch (RuntimeException) {
            return response()->json([
                'message' => 'Organization not found.',
            ], 404);
        }

        return (new OrganizationResource($organization))
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(string $uuid): JsonResponse
    {
        try {
            $this->archiveOrganization->execute($uuid);
        } catch (RuntimeException) {
            return response()->json([
                'message' => 'Organization not found.',
            ], 404);
        }

        return response()->json([], 204);
    }

    public function restore(string $uuid): JsonResponse
{
    try {
        $organization = $this->restoreOrganization->execute($uuid);
    } catch (RuntimeException) {
        return response()->json([
            'message' => 'Organization not found.',
        ], 404);
    }

    return (new OrganizationResource($organization))
        ->response()
        ->setStatusCode(200);
}
    
}