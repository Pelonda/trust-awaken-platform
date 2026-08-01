<?php

declare(strict_types=1);

namespace App\Core\Organization\DTOs;

final readonly class CreateOrganizationData
{
    public function __construct(
        public string $displayName,
        public ?string $legalName,
        public string $organizationType,
        public ?int $ownerUserId = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            displayName: $data['display_name'],
            legalName: $data['legal_name'] ?? null,
            organizationType: $data['organization_type'],
            ownerUserId: $data['owner_user_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'display_name'      => $this->displayName,
            'legal_name'        => $this->legalName,
            'organization_type' => $this->organizationType,
            'owner_user_id'     => $this->ownerUserId,
        ];
    }
}