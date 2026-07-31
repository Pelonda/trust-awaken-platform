<?php

declare(strict_types=1);

namespace App\Core\Organization\ValueObjects;

use InvalidArgumentException;

final readonly class OrganizationName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidArgumentException('Organization name cannot be empty.');
        }

        if (mb_strlen($value) > 255) {
            throw new InvalidArgumentException('Organization name cannot exceed 255 characters.');
        }

        $this->value = $value;
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}