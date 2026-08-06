<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_brands', function (Blueprint $table): void {

            $table->id();

            $table->uuid()->unique();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('brand_name', 150);

            $table->string('logo_path')->nullable();

            $table->string('favicon_path')->nullable();

            $table->string('background_path')->nullable();

            $table->string('watermark_path')->nullable();

            $table->string('signature_path')->nullable();

            $table->string('seal_path')->nullable();

            $table->string('primary_color', 20)
                ->default('#0F4C81');

            $table->string('secondary_color', 20)
                ->default('#F59E0B');

            $table->string('accent_color', 20)
                ->default('#2563EB');

            $table->string('font_family', 100)
                ->default('DejaVu Sans');

            $table->json('settings')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->unique('organization_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_brands');
    }
};