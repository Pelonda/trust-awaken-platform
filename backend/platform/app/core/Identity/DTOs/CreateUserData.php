<?php

declare(strict_types=1);

namespace App\Core\Identity\DTOs;

final readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
    }
}