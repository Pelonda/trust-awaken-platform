<?php

declare(strict_types=1);

namespace App\Core\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Permission extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'display_name',
        'description',
        'system',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'permission_role'
        );
    }
}