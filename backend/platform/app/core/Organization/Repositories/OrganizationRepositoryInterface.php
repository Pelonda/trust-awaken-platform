<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Organization\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrganizationRepositoryInterface
{
    /**
     * Create a new organization.
     */
    public function create(
        CreateOrganizationData $data
    ): Organization;

    /**
     * Find an organization by UUID.
     */
    public function findByUuid(
        string $uuid
    ): ?Organization;

    /**
     * Find an organization by slug.
     */
    public function findBySlug(
        string $slug
    ): ?Organization;

    /**
     * Find a soft-deleted organization by UUID.
     */
    public function findTrashedByUuid(
        string $uuid
    ): ?Organization;

    /**
     * Paginate organizations.
     */
    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator;

    /**
 * Update an organization.
 */
public function update(
    Organization $organization,
    array $attributes
): Organization;

/**
 * Soft delete an organization.
 */
public function delete(
    Organization $organization
): void;

/**
 * Restore a soft-deleted organization.
 */
public function restore(
    Organization $organization
): void;

}