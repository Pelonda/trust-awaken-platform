<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credential_templates', function (Blueprint $table): void {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('template_code', 50)->unique();

            $table->string('name', 255);

            $table->string('credential_type', 50);

            $table->string('paper_size', 20)
                ->default('A4');

            $table->string('orientation', 20)
                ->default('landscape');

            $table->string('background_image')->nullable();

            $table->json('elements')->nullable();

            $table->boolean('is_default')
                ->default(false);

            $table->timestamps();

            $table->softDeletes();

            $table->index('organization_id');
            $table->index('credential_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credential_templates');
    }
};