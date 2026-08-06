<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_assets', function (Blueprint $table): void {

            $table->id();

            $table->uuid()->unique();

            $table->foreignId('organization_id')
                ->nullable()
                ->constrained('organizations')
                ->nullOnDelete();

            $table->string('name', 150);

            $table->string('asset_type', 50)
                ->default('image');

            $table->string('disk', 50)
                ->default('public');

            $table->string('path');

            $table->string('mime_type', 100)->nullable();

            $table->unsignedInteger('width')->nullable();

            $table->unsignedInteger('height')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index([
                'organization_id',
                'asset_type',
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_assets');
    }
};