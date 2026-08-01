<?php

declare(strict_types=1);

namespace App\Core\Organization\Actions;

use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Organization\Models\Organization;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;

final readonly class CreateOrganization
{
    public function __construct(
        private OrganizationRepositoryInterface $repository,
    ) {
    }

    public function execute(
        CreateOrganizationData $data,
    ): Organization {
        return $this->repository->create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'slug' => \Illuminate\Support\Str::slug($data->displayName),
            'display_name' => $data->displayName,
            'legal_name' => $data->legalName,
            'organization_type' => $data->organizationType,
            'owner_user_id' => $data->ownerUserId,
            'status' => 'draft',
        ]);
    }
}