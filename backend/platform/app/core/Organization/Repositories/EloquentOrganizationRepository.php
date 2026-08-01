<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Organization\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

final class EloquentOrganizationRepository implements OrganizationRepositoryInterface
{
    public function create(
        CreateOrganizationData $data
    ): Organization {
        return Organization::create([
            'uuid' => (string) Str::uuid(),

            'slug' => Str::slug($data->displayName),

            'display_name' => $data->displayName,

            'legal_name' => $data->legalName,

            'organization_type' => $data->organizationType,

            'owner_user_id' => $data->ownerUserId,

            'status' => 'draft',
        ]);
    }

    public function update(
    Organization $organization,
    array $attributes
): Organization {

    $organization->update($attributes);

    return $organization->refresh();
}

    public function findByUuid(
        string $uuid
    ): ?Organization {
        return Organization::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function findBySlug(
        string $slug
    ): ?Organization {
        return Organization::query()
            ->where('slug', $slug)
            ->first();
    }

    public function findTrashedByUuid(
        string $uuid
    ): ?Organization {
        return Organization::onlyTrashed()
            ->where('uuid', $uuid)
            ->first();
    }

    public function delete(
    Organization $organization
): void {
    $organization->delete();
}

public function restore(
    Organization $organization
): void {
    $organization->restore();
}

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator {
        return Organization::query()
            ->orderBy('display_name')
            ->paginate($perPage);
    }
}