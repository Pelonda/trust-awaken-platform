<?php

declare(strict_types=1);

namespace App\Core\Identity\ValueObjects;

use InvalidArgumentException;

final readonly class Password
{
    public function __construct(
        public string $value
    ) {
        if (strlen($value) < 12) {
            throw new InvalidArgumentException(
                'Password must contain at least 12 characters.'
            );
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}