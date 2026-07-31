<?php

declare(strict_types=1);

namespace App\Core\Organization\Actions;

use App\Core\Organization\DTOs\CreateOrganizationData;
use App\Core\Organization\Models\Organization;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;
use App\Core\Organization\ValueObjects\OrganizationName;
use App\Core\Organization\ValueObjects\OrganizationSlug;
use App\Core\Organization\ValueObjects\OrganizationUuid;

final readonly class CreateOrganization
{
    public function __construct(
        private OrganizationRepositoryInterface $repository,
    ) {
    }

    public function execute(
        CreateOrganizationData $data,
        int $ownerUserId,
    ): Organization {
        $organization = Organization::register(
            uuid: OrganizationUuid::generate(),
            slug: OrganizationSlug::fromDisplayName($data->displayName),
            displayName: OrganizationName::fromString($data->displayName),
            legalName: OrganizationName::fromString($data->legalName),
            organizationType: $data->organizationType,
            ownerUserId: $ownerUserId,
        );

        return $this->repository->save($organization);
    }
}