<?php

declare(strict_types=1);

namespace App\Core\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class DocumentTemplateElement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'template_id',
        'element_type',
        'name',
        'x',
        'y',
        'width',
        'height',
        'rotation',
        'z_index',
        'locked',
        'visible',
        'properties',
    ];

    protected function casts(): array
    {
        return [
            'locked' => 'boolean',
            'visible' => 'boolean',
            'properties' => 'array',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(
            DocumentTemplate::class,
            'template_id'
        );
    }
}
