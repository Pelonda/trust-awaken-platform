<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\DTOs;

final readonly class CreateCredentialTemplateData
{
    public function __construct(
        public int $organizationId,
        public string $templateCode,
        public string $name,
        public string $credentialType,
        public string $paperSize,
        public string $orientation,
        public ?string $backgroundImage,
        public ?array $elements,
        public bool $isDefault,
    ) {
    }
}