<?php

declare(strict_types=1);

namespace App\Core\Credential\DTOs;

final readonly class CreateCredentialData
{
    public function __construct(
        public int $organizationId,
        public int $participantId,
        public int $programId,
        public int $sessionId,
        public int $templateId,
        public string $credentialType,
        public ?string $expiresAt,
        public ?array $metadata,
    ) {
    }
}