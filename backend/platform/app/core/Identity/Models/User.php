<?php

declare(strict_types=1);

namespace App\Core\Identity\Models;

use App\Models\User as BaseUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class User extends BaseUser
{
    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            \App\Core\Organization\Models\Organization::class
        );
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_user'
        );
    }
}