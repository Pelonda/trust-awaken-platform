<?php

declare(strict_types=1);

namespace App\Core\Registration\DTOs;

final readonly class RegisterOrganizationData
{
    public function __construct(
        public string $organizationName,
        public string $legalName,
        public string $organizationType,

        public string $ownerName,
        public string $ownerEmail,
        public string $password,
    ) {
    }
}