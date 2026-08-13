<?php

declare(strict_types=1);

namespace App\Core\Program\Models;

use App\Core\ProgramEnrollment\Models\ProgramEnrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /*
    |--------------------------------------------------------------------------
    | Program Enrollments
    |--------------------------------------------------------------------------
    |
    | A program can contain many participant enrollment records.
    |
    */

    public function enrollments(): HasMany
    {
        return $this->hasMany(
            ProgramEnrollment::class,
            'program_id'
        );
    }
}