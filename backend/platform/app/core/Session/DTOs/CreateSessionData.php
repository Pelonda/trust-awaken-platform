<?php

declare(strict_types=1);

namespace App\Core\Session\DTOs;

final readonly class CreateSessionData
{
    public function __construct(
        public int $programId,
        public string $sessionCode,
        public string $title,
        public ?string $description,
        public int $sessionNumber,
        public string $startsAt,
        public string $endsAt,
        public ?string $venue,
    ) {
    }
}