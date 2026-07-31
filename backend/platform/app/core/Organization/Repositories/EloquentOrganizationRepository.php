<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\Models\Organization;

final class EloquentOrganizationRepository implements OrganizationRepositoryInterface
{
    public function create(array $data): Organization
    {
        return Organization::create($data);
    }

    public function update(
        Organization $organization,
        array $data
    ): Organization {
        $organization->update($data);

        return $organization;
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