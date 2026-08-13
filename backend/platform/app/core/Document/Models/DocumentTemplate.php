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

    protected $table =
        'document_templates';

    protected $fillable = [
        'uuid',
        'organization_id',

        'name',

        /*
         * Legacy compatibility.
         */

        'type',
        'paper_size',
        'orientation',

        /*
         * Fabric document.
         */

        'canvas',

        'default',

        /*
         * Template Schema V2.
         */

        'schema_version',
        'document_type',
        'language',

        'paper_width',
        'paper_height',
        'paper_unit',

        'is_system',

        'source_template_id',

        'settings',
    ];

    protected function casts():
        array {
        return [
            'canvas' =>
                'array',

            'default' =>
                'boolean',

            'schema_version' =>
                'integer',

            'paper_width' =>
                'float',

            'paper_height' =>
                'float',

            'is_system' =>
                'boolean',

            'settings' =>
                'array',
        ];
    }

    public function organization():
        BelongsTo {
        return $this->belongsTo(
            \App\Core\Organization\Models\Organization::class
        );
    }

    public function elements():
        HasMany {
        return $this->hasMany(
            DocumentTemplateElement::class,
            'template_id'
        );
    }

    public function sourceTemplate():
        BelongsTo {
        return $this->belongsTo(
            self::class,
            'source_template_id'
        );
    }

    public function derivedTemplates():
        HasMany {
        return $this->hasMany(
            self::class,
            'source_template_id'
        );
    }

    public function isSystemTemplate():
        bool {
        return
            $this->is_system ===
            true;
    }

    public function isV2():
        bool {
        return
            $this->schema_version >=
            2;
    }

    public function hasBackSide():
        bool {
        return in_array(
            $this->document_type,
            [
                'id_card',
                'training_card',
            ],
            true
        );
    }
}