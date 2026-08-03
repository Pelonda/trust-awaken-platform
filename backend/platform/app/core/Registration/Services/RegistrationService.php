<?php

declare(strict_types=1);

namespace App\Presentation\Api\Public\Controllers;

use App\Core\Registration\Actions\RegisterOrganization;
use App\Core\Registration\DTOs\RegisterOrganizationData;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Public\Requests\RegisterOrganizationRequest;
use Illuminate\Http\JsonResponse;

final class RegistrationController extends Controller
{
    public function __construct(
        private readonly RegisterOrganization $registerOrganization,
    ) {
    }

    public function register(
        RegisterOrganizationRequest $request,
    ): JsonResponse {

        $result = $this->registerOrganization->execute(
            new RegisterOrganizationData(
                organizationName: $request->string('organization_name')->toString(),
                legalName: $request->string('legal_name')->toString(),
                organizationType: $request->string('organization_type')->toString(),
                ownerName: $request->string('owner_name')->toString(),
                ownerEmail: $request->string('owner_email')->toString(),
                password: $request->string('password')->toString(),
            )
        );

        return response()->json(
            $result,
            201
        );
    }
}