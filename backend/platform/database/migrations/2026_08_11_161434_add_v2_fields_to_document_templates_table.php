<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'document_templates',
            function (Blueprint $table): void {
                /*
                |--------------------------------------------------------------------------
                | Template Schema Version
                |--------------------------------------------------------------------------
                |
                | Existing templates remain schema version 1.
                | New Document Studio templates can use schema version 2.
                |
                */

                $table
                    ->unsignedSmallInteger('schema_version')
                    ->default(1);

                /*
                |--------------------------------------------------------------------------
                | Document Classification
                |--------------------------------------------------------------------------
                |
                | Examples:
                |
                | certificate
                | diploma
                | badge
                | id_card
                | training_card
                | custom
                |
                */

                $table
                    ->string(
                        'document_type',
                        50
                    )
                    ->default('certificate');

                /*
                |--------------------------------------------------------------------------
                | Template Language
                |--------------------------------------------------------------------------
                |
                | en    = English
                | fr    = French
                | en-fr = bilingual English/French
                |
                */

                $table
                    ->string(
                        'language',
                        20
                    )
                    ->default('en');

                /*
                |--------------------------------------------------------------------------
                | Physical Paper Dimensions
                |--------------------------------------------------------------------------
                |
                | These are independent from Fabric canvas coordinates.
                |
                | Examples:
                |
                | A4:
                | 297 x 210 mm landscape
                |
                | CR80 / ID-1:
                | 85.60 x 53.98 mm
                |
                */

                $table
                    ->decimal(
                        'paper_width',
                        10,
                        3
                    )
                    ->nullable();

                $table
                    ->decimal(
                        'paper_height',
                        10,
                        3
                    )
                    ->nullable();

                $table
                    ->string(
                        'paper_unit',
                        10
                    )
                    ->default('mm');

                /*
                |--------------------------------------------------------------------------
                | System Template Library
                |--------------------------------------------------------------------------
                |
                | System templates are professional Trust AWAKEN starter
                | designs.
                |
                | A tenant copies a system template and manages its own copy.
                |
                */

                $table
                    ->boolean('is_system')
                    ->default(false);

                /*
                |--------------------------------------------------------------------------
                | Template Lineage
                |--------------------------------------------------------------------------
                |
                | When a tenant copies a professional system template,
                | source_template_id identifies the original template.
                |
                */

                $table
                    ->foreignId(
                        'source_template_id'
                    )
                    ->nullable()
                    ->constrained(
                        'document_templates'
                    )
                    ->nullOnDelete();

                /*
                |--------------------------------------------------------------------------
                | V2 Settings
                |--------------------------------------------------------------------------
                |
                | Reserved for extensible template configuration:
                |
                | - pages / sides
                | - bleed
                | - safe area
                | - export settings
                | - template library metadata
                | - additional language settings
                |
                */

                $table
                    ->json('settings')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Indexes
                |--------------------------------------------------------------------------
                */

                $table->index(
                    [
                        'organization_id',
                        'document_type',
                    ],
                    'document_templates_org_type_idx'
                );

                $table->index(
                    [
                        'is_system',
                        'document_type',
                        'language',
                    ],
                    'document_templates_library_idx'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'document_templates',
            function (Blueprint $table): void {
                $table->dropIndex(
                    'document_templates_org_type_idx'
                );

                $table->dropIndex(
                    'document_templates_library_idx'
                );

                $table->dropForeign(
                    [
                        'source_template_id',
                    ]
                );

                $table->dropColumn([
                    'schema_version',
                    'document_type',
                    'language',
                    'paper_width',
                    'paper_height',
                    'paper_unit',
                    'is_system',
                    'source_template_id',
                    'settings',
                ]);
            }
        );
    }
};