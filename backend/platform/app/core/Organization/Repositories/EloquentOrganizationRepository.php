<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\Models\Organization;

final class EloquentOrganizationRepository implements OrganizationRepositoryInterface
{
    public function create(
        string $uuid,
        string $slug,
        string $displayName,
        string $legalName,
        string $organizationType,
        string $status,
        int $ownerUserId,
    ): Organization {
        return Organization::create([
            'uuid' => $uuid,
            'slug' => $slug,
            'display_name' => $displayName,
            'legal_name' => $legalName,
            'organization_type' => $organizationType,
            'status' => $status,
            'owner_user_id' => $ownerUserId,
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