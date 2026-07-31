<?php

declare(strict_types=1);

namespace App\Presentation\Api\Organization\Controllers;

use App\Core\Organization\Actions\CreateOrganization;
use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Organization\Requests\StoreOrganizationRequest;
use App\Presentation\Api\Organization\Resources\OrganizationResource;
use Illuminate\Http\JsonResponse;

final class OrganizationController extends Controller
{
    /**
     * Temporary owner until the Identity module is implemented.
     */
    private const TEMP_OWNER_ID = 1;

    public function __construct(
        private readonly CreateOrganization $createOrganization,
    ) {
    }

    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $organization = $this->createOrganization->execute(
            data: new CreateOrganizationData(
                displayName: $request->string('display_name')->toString(),
                legalName: $request->string('legal_name')->toString(),
                organizationType: $request->string('organization_type')->toString(),
            ),
            ownerUserId: self::TEMP_OWNER_ID,
        );

        return (new OrganizationResource($organization))
            ->response()
            ->setStatusCode(201);
    }
}