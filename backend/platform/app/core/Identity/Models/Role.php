<?php

declare(strict_types=1);

namespace App\Core\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Role extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'display_name',
        'description',
        'system',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'role_user'
        );
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role'
        );
    }
}