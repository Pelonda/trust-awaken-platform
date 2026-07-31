<?php

declare(strict_types=1);

namespace App\Core\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class User extends Model
{
    use SoftDeletes;

    protected $table = 'identity_users';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public static function register(
        string $uuid,
        string $name,
        string $email,
        string $password,
    ): self {
        return new self([
            'uuid' => $uuid,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'is_active' => true,
        ]);
    }
}