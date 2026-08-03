<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class CredentialTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'credential_templates';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'elements' => 'array',
            'is_default' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}