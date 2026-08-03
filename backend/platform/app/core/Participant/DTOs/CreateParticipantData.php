<?php

declare(strict_types=1);

namespace App\Core\Participant\DTOs;

final readonly class CreateParticipantData
{
    public function __construct(
        public int $organizationId,
        public string $participantCode,
        public string $firstName,
        public string $lastName,
        public ?string $email,
        public ?string $phone,
        public ?string $dateOfBirth,
        public ?string $gender,
        public ?string $country,
    ) {
    }
}