<?php

declare(strict_types=1);

namespace App\Core\Organization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Organization extends Model
{
    use SoftDeletes;

    protected $table = 'organizations';

    protected $guarded = [];

    /**
     * Register a new Organization aggregate.
     */
    public static function register(
        string $uuid,
        string $slug,
        string $displayName,
        string $legalName,
        string $organizationType,
        int $ownerUserId,
    ): self {
        return new self([
            'uuid' => $uuid,
            'slug' => $slug,
            'display_name' => $displayName,
            'legal_name' => $legalName,
            'organization_type' => $organizationType,
            'status' => 'draft',
            'owner_user_id' => $ownerUserId,
        ]);
    }
}