<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\DTOs;

final readonly class UpdateCredentialTemplateData
{
    public function __construct(
        public string $name,
        public string $paperSize,
        public string $orientation,
        public bool $isDefault,
    ) {
    }
}