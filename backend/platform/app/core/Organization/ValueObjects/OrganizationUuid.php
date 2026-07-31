<?php

declare(strict_types=1);

namespace App\Core\Organization\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

final readonly class OrganizationUuid
{
    public function __construct(
        private string $value,
    ) {
        if (! Str::isUuid($this->value)) {
            throw new InvalidArgumentException('Invalid organization UUID.');
        }
    }

    public static function generate(): self
    {
        return new self((string) Str::uuid());
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
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