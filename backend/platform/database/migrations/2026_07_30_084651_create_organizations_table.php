<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table): void {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('slug', 150)->unique();

            $table->string('display_name', 255);

            $table->string('legal_name', 255);

            $table->string('organization_type', 50)
                ->default('company');

            $table->string('status', 30)
                ->default('draft');

            $table->foreignId('owner_user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            $table->softDeletes();

            $table->index('organization_type');
            $table->index('status');
            $table->index('owner_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};