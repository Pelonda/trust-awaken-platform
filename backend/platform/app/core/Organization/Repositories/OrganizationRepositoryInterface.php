<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\Models\Organization;

interface OrganizationRepositoryInterface
{
    public function create(array $data): Organization;

    public function update(
        Organization $organization,
        array $data
    ): Organization;

    public function findByUuid(string $uuid): ?Organization;

    public function findBySlug(string $slug): ?Organization;
}