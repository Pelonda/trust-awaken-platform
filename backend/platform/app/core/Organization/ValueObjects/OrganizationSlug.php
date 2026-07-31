<?php

declare(strict_types=1);

namespace App\Core\Organization\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

final readonly class OrganizationSlug
{
    public function __construct(
        private string $value,
    ) {
        if ($this->value === '') {
            throw new InvalidArgumentException('Organization slug cannot be empty.');
        }
    }

    public static function fromDisplayName(string $displayName): self
    {
        return new self(Str::slug($displayName));
    }

    public static function fromString(string $slug): self
    {
        return new self(Str::lower(trim($slug)));
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