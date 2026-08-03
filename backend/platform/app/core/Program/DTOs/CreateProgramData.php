<?php

declare(strict_types=1);

namespace App\Core\Program\DTOs;

final readonly class CreateProgramData
{
    public function __construct(
        public int $organizationId,
        public string $programCode,
        public string $title,
        public ?string $description,
        public string $programType,
        public string $deliveryMode,
        public ?string $startsAt,
        public ?string $endsAt,
        public ?string $venue,
        public ?int $capacity,
        public string $language,
        public bool $credentialEnabled,
        public ?int $createdBy,
    ) {
    }
}