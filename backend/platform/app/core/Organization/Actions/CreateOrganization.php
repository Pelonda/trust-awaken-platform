<?php

declare(strict_types=1);

namespace App\Core\Organization\Actions;

use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Organization\Models\Organization;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;

final readonly class CreateOrganization
{
    public function __construct(
        private OrganizationRepositoryInterface $repository,
    ) {
    }

    public function execute(CreateOrganizationData $data): Organization
    {
        return $this->repository->create($data);
    }
}