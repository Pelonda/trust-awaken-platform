<?php

declare(strict_types=1);

namespace App\Core\Identity\ValueObjects;

use InvalidArgumentException;

final readonly class EmailAddress
{
    public function __construct(
        public string $value
    ) {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'Invalid email address.'
            );
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}