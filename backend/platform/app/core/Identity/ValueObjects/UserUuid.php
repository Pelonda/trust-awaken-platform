<?php

declare(strict_types=1);

namespace App\Core\Identity\ValueObjects;

use Illuminate\Support\Str;

final readonly class UserUuid
{
    public function __construct(
        public string $value
    ) {
    }

    public static function generate(): self
    {
        return new self(
            (string) Str::uuid()
        );
    }

    public function __toString(): string
    {
        return $this->value;
    }
}