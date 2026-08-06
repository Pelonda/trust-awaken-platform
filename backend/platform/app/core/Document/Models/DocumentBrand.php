<?php

declare(strict_types=1);

namespace App\Core\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class DocumentBrand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'organization_id',
        'brand_name',
        'logo_path',
        'favicon_path',
        'background_path',
        'watermark_path',
        'signature_path',
        'seal_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'font_family',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            \App\Core\Organization\Models\Organization::class
        );
    }
}