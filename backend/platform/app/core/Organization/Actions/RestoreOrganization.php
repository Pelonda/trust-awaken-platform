<?php

declare(strict_types=1);

namespace App\Core\Organization\Actions;

use App\Core\Organization\Models\Organization;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;
use RuntimeException;

final readonly class RestoreOrganization
{
    public function __construct(
        private OrganizationRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): Organization
    {
        $organization = $this->repository->findTrashedByUuid($uuid);

        if ($organization === null) {
            throw new RuntimeException('Organization not found.');
        }

        $this->repository->restore($organization);

        return $organization->fresh();
    }
}