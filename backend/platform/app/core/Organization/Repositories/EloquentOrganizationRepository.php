<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\Models\Organization;

final class EloquentOrganizationRepository implements OrganizationRepositoryInterface
{
    public function save(Organization $organization): Organization
    {
        $organization->save();

        return $organization->refresh();
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

    public function paginate(int $perPage = 15)
    {
        return Organization::query()
            ->orderBy('display_name')
            ->paginate($perPage);
    }

    public function findTrashedByUuid(string $uuid): ?Organization
    {
        return Organization::onlyTrashed()
            ->where('uuid', $uuid)
            ->first();
    }

    public function restore(Organization $organization): void
    {
        $organization->restore();
    }
}