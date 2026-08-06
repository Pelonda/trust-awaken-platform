<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table): void {

            $table->id();

            $table->uuid()->unique();

            $table->foreignId('organization_id')
                ->nullable()
                ->constrained('organizations')
                ->nullOnDelete();

            $table->string('name', 150);

            $table->string('type', 50);

            $table->string('paper_size', 30)
                ->default('A4');

            $table->string('orientation', 20)
                ->default('landscape');

            $table->json('canvas');

            $table->boolean('default')
                ->default(false);

            $table->timestamps();

            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};