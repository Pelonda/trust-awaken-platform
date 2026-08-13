<?php

declare(strict_types=1);

namespace App\Core\ProgramEnrollment\Models;

use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ProgramEnrollment extends Model
{
    protected $table =
        'program_enrollments';

    protected $guarded = [];

    protected $primaryKey =
        'id';

    public $incrementing =
        true;

    protected $keyType =
        'int';

    protected function casts(): array
    {
        return [
            'enrolled_at' =>
                'datetime',

            'completed_at' =>
                'datetime',

            'metadata' =>
                'array',

            'created_at' =>
                'datetime',

            'updated_at' =>
                'datetime',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(
            Program::class,
            'program_id'
        );
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(
            Participant::class,
            'participant_id'
        );
    }
}