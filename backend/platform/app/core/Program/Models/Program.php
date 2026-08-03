<?php

declare(strict_types=1);

namespace App\Core\Program\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Program extends Model
{
    use SoftDeletes;

    protected $table = 'programs';

    protected $guarded = [];

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'credential_enabled' => 'boolean',
        ];
    }
}