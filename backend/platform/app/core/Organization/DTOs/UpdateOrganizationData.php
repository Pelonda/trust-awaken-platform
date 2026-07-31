<?php

declare(strict_types=1);

namespace App\Core\Organization\DTOs;

final readonly class UpdateOrganizationData
{
    public function __construct(
        public string $displayName,
        public string $legalName,
        public string $organizationType,
    ) {
    }
}