<?php

declare(strict_types=1);

namespace App\Core\Organization\Actions;

use App\Core\Organization\Repositories\OrganizationRepositoryInterface;
use RuntimeException;

final readonly class ArchiveOrganization
{
    public function __construct(
        private OrganizationRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): void
    {
        $organization = $this->repository->findByUuid($uuid);

        if ($organization === null) {
            throw new RuntimeException('Organization not found.');
        }

        $this->repository->delete($organization);
    }
}