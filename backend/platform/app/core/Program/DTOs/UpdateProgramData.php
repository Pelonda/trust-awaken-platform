<?php

declare(strict_types=1);

namespace App\Core\Program\DTOs;

final readonly class UpdateProgramData
{
    public function __construct(
        public string $title,
        public ?string $description,
        public string $programType,
        public string $deliveryMode,
    ) {
    }
}