<?php

declare(strict_types=1);

namespace App\Core\Participant\Models;

use App\Core\ProgramEnrollment\Models\ProgramEnrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Participant extends Model
{
    use SoftDeletes;

    protected $table = 'participants';

    protected $guarded = [];

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Program Enrollments
    |--------------------------------------------------------------------------
    |
    | A participant may be enrolled in multiple programs.
    |
    */

    public function programEnrollments(): HasMany
    {
        return $this->hasMany(
            ProgramEnrollment::class,
            'participant_id'
        );
    }
}