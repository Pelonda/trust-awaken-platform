<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DocumentTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'organization_id',
        'name',
        'type',
        'paper_size',
        'orientation',
        'canvas',
        'default',
    ];

    protected function casts(): array
    {
        return [
            'canvas' => 'array',
            'default' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DocumentTemplate $template) {
            if (!$template->uuid) {
                $template->uuid = (string) Str::uuid();
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class
        );
    }
}