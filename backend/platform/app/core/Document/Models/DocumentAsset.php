<?php

declare(strict_types=1);

namespace App\Core\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class DocumentAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'organization_id',
        'name',
        'asset_type',
        'disk',
        'path',
        'mime_type',
        'width',
        'height',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            \App\Core\Organization\Models\Organization::class
        );
    }
}