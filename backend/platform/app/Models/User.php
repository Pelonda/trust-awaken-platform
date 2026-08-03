<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The primary key is the auto-increment BIGINT "id".
     */
    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'user_type',
        'status',
        'last_login_at',
    ];

    /**
     * Hidden attributes.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'deleted_at'        => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Register a new user.
     */
    public static function register(
        string $uuid,
        string $name,
        string $email,
        string $password,
    ): self {
        return new self([
            'uuid'          => $uuid,
            'name'          => $name,
            'email'         => $email,
            'password'      => $password,
            'user_type'     => 'organization',
            'status'        => 'active',
            'last_login_at' => null,
        ]);
    }
}