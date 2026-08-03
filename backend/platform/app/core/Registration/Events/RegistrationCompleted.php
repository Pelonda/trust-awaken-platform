<?php

declare(strict_types=1);

namespace App\Core\Registration\Events;

final readonly class RegistrationCompleted
{
    public function __construct(
        public int $userId,
        public int $organizationId,
    ) {
    }
}