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
            'credentials',
            function (Blueprint $table): void {
                $table
                    ->foreignId(
                        'document_template_id'
                    )
                    ->nullable()
                    ->after('template_id')
                    ->constrained(
                        'document_templates'
                    )
                    ->nullOnDelete();

                $table->index(
                    [
                        'organization_id',
                        'document_template_id',
                    ],
                    'credentials_org_document_template_idx'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'credentials',
            function (Blueprint $table): void {
                $table->dropIndex(
                    'credentials_org_document_template_idx'
                );

                $table->dropConstrainedForeignId(
                    'document_template_id'
                );
            }
        );
    }
};