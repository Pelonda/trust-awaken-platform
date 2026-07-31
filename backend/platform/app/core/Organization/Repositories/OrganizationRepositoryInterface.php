<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\Models\Organization;

interface OrganizationRepositoryInterface
{
    public function create(array $attributes): Organization;

    public function update(Organization $organization, array $attributes): Organization;

    public function delete(Organization $organization): void;

    public function findById(int $id): ?Organization;

    public function findByUuid(string $uuid): ?Organization;

    public function findBySlug(string $slug): ?Organization;
}