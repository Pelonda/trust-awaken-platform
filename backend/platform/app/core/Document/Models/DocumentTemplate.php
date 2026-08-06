<?php

declare(strict_types=1);

namespace App\Core\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class DocumentTemplate extends Model
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

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            \App\Core\Organization\Models\Organization::class
        );
    }

    public function elements(): HasMany
    {
        return $this->hasMany(
            DocumentTemplateElement::class,
            'template_id'
        );
    }
}