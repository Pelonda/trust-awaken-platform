<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_template_elements', function (Blueprint $table): void {

            $table->id();

            $table->uuid()->unique();

            $table->foreignId('template_id')
                ->constrained('document_templates')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('element_type', 50);

            $table->string('name', 150);

            $table->integer('x');

            $table->integer('y');

            $table->integer('width')
                ->default(100);

            $table->integer('height')
                ->default(40);

            $table->integer('rotation')
                ->default(0);

            $table->integer('z_index')
                ->default(1);

            $table->boolean('locked')
                ->default(false);

            $table->boolean('visible')
                ->default(true);

            $table->json('properties');

            $table->timestamps();

            $table->softDeletes();

            $table->index([
                'template_id',
                'element_type',
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'document_template_elements'
        );
    }
};