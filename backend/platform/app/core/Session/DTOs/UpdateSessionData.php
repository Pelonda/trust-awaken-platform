<?php

declare(strict_types=1);

namespace App\Core\Session\DTOs;

final readonly class UpdateSessionData
{
    public function __construct(
        public string $title,
        public ?string $description,
    ) {
    }
}