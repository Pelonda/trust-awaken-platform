<?php

declare(strict_types=1);

namespace App\Core\Registration\Services;

use App\Core\Identity\Actions\CreateUser;
use App\Core\Identity\DTOs\CreateUserData;
use App\Core\Organization\Actions\CreateOrganization;
use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Registration\DTOs\RegisterOrganizationData;
use Illuminate\Support\Facades\DB;

final readonly class RegistrationService
{
    public function __construct(
        private CreateUser $createUser,
        private CreateOrganization $createOrganization,
    ) {
    }

    public function register(
        RegisterOrganizationData $data,
    ): array {

        return DB::transaction(function () use ($data): array {

            $user = $this->createUser->execute(
                new CreateUserData(
                    name: $data->ownerName,
                    email: $data->ownerEmail,
                    password: $data->password,
                )
            );

            $organization = $this->createOrganization->execute(
                data: new CreateOrganizationData(
                    displayName: $data->organizationName,
                    legalName: $data->legalName,
                    organizationType: $data->organizationType,
                ),
                ownerUserId: $user->id,
            );

            return [
            'message' => 'Registration completed successfully.',
            'user_uuid' => $user->uuid,
            'organization_uuid' => $organization->uuid,
            'token' => $user->createToken('registration')->plainTextToken,
            ];
        });
    }
}