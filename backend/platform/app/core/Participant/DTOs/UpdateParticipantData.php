<?php

declare(strict_types=1);

namespace App\Core\Participant\DTOs;

final readonly class UpdateParticipantData
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?string $email,
        public ?string $phone,
        public ?string $gender,
        public ?string $country,
    ) {
    }
}
