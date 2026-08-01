<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Organization\Models\Organization;

interface OrganizationRepositoryInterface
{
    public function create(
        CreateOrganizationData $data
    ): Organization;

    public function findByUuid(
        string $uuid
    ): ?Organization;

    public function findBySlug(
        string $slug
    ): ?Organization;
}