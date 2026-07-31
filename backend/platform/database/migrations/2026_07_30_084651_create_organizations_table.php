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

            // Primary Key
            $table->id();

            // Public Identifier
            $table->uuid('uuid')->unique();

            // Public URL
            $table->string('slug', 100)->unique();

            // Display
            $table->string('display_name', 200);

            $table->string('legal_name', 255);

            // Classification
            $table->string('organization_type', 50)
                  ->default('company');

            // Lifecycle
            $table->string('status', 30)
                  ->default('draft');

            // Owner
            $table->foreignId('owner_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // Audit
            $table->timestamps();

            $table->softDeletes();

            // Indexes
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