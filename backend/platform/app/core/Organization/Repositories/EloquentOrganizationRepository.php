<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Organization\Models\Organization;

final class EloquentOrganizationRepository implements OrganizationRepositoryInterface
{
    public function create(CreateOrganizationData $data): Organization
    {
    return Organization::create([
        'uuid' => $data->uuid,
        'slug' => $data->slug,
        'display_name' => $data->displayName,
        'legal_name' => $data->legalName,
        'organization_type' => $data->organizationType,
        'status' => $data->status,
        'owner_user_id' => $data->ownerUserId,
    ]);
    }

    public function update(Organization $organization, array $attributes): Organization
    {
        $organization->update($attributes);

        return $organization->refresh();
    }

    public function delete(Organization $organization): void
    {
        $organization->delete();
    }

    public function findById(int $id): ?Organization
    {
        return Organization::find($id);
    }

    public function findByUuid(string $uuid): ?Organization
    {
        return Organization::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function findBySlug(string $slug): ?Organization
    {
        return Organization::query()
            ->where('slug', $slug)
            ->first();
    }
}