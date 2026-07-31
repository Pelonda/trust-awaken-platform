<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\Models\Organization;
use App\Core\Organization\DTOs\CreateOrganizationData;

interface OrganizationRepositoryInterface
{
    public function save(Organization $organization): Organization;

    public function update(Organization $organization, array $attributes): Organization;

    public function delete(Organization $organization): void;

    public function findById(int $id): ?Organization;

    public function findByUuid(string $uuid): ?Organization;

    public function findBySlug(string $slug): ?Organization;
    public function paginate(int $perPage = 15);
    public function findTrashedByUuid(string $uuid): ?Organization;

    public function restore(Organization $organization): void;
}