<?php

declare(strict_types=1);

namespace App\Core\Organization\Models;

use App\Core\Organization\ValueObjects\OrganizationName;
use App\Core\Organization\ValueObjects\OrganizationSlug;
use App\Core\Organization\ValueObjects\OrganizationUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Organization extends Model
{
    use SoftDeletes;

    protected $table = 'organizations';

    protected $guarded = [];

    public static function register(
        OrganizationUuid $uuid,
        OrganizationSlug $slug,
        OrganizationName $displayName,
        OrganizationName $legalName,
        string $organizationType,
        int $ownerUserId,
    ): self {
        return new self([
            'uuid' => $uuid->value(),
            'slug' => $slug->value(),
            'display_name' => $displayName->value(),
            'legal_name' => $legalName->value(),
            'organization_type' => $organizationType,
            'status' => 'draft',
            'owner_user_id' => $ownerUserId,
        ]);
    }
}