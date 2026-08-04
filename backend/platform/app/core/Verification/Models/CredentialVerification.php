<?php

declare(strict_types=1);

namespace App\Core\Verification\Models;

use Illuminate\Database\Eloquent\Model;

final class CredentialVerification extends Model
{
    protected $table = 'credential_verifications';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'valid' => 'boolean',
            'verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}