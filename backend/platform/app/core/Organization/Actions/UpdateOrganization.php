<?php

declare(strict_types=1);

namespace App\Core\Organization\Actions;

use App\Core\Organization\DTOs\UpdateOrganizationData;
use App\Core\Organization\Models\Organization;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;
use RuntimeException;

final readonly class UpdateOrganization
{
    public function __construct(
        private OrganizationRepositoryInterface $repository,
    ) {
    }

    public function execute(
        string $uuid,
        UpdateOrganizationData $data,
    ): Organization {
        $organization = $this->repository->findByUuid($uuid);

        if ($organization === null) {
            throw new RuntimeException('Organization not found.');
        }

        return $this->repository->update(
            $organization,
            [
                'display_name' => $data->displayName,
                'legal_name' => $data->legalName,
                'organization_type' => $data->organizationType,
            ]
        );
    }
}