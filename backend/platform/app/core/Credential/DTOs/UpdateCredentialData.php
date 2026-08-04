<?php

declare(strict_types=1);

namespace App\Core\Credential\DTOs;

final readonly class UpdateCredentialData
{
    public function __construct(
        public string $status,
    ) {
    }
}