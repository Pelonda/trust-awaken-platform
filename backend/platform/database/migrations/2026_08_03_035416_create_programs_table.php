<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table): void {

            // Primary Key
            $table->id();

            // Public Identifier
            $table->uuid('uuid')->unique();

            // Organization
            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Public Code
            $table->string('program_code', 50)->unique();

            // Information
            $table->string('title', 255);

            $table->text('description')->nullable();

            // Classification
            $table->string('program_type', 50);

            $table->string('delivery_mode', 30)
                ->default('in_person');

            // Status
            $table->string('status', 30)
                ->default('draft');

            // Schedule
            $table->timestamp('starts_at')->nullable();

            $table->timestamp('ends_at')->nullable();

            $table->string('timezone', 100)
                ->default('UTC');

            // Venue
            $table->string('venue', 255)
                ->nullable();

            // Capacity
            $table->unsignedInteger('capacity')
                ->nullable();

            // Language
            $table->string('language', 20)
                ->default('en');

            // Credentials
            $table->boolean('credential_enabled')
                ->default(true);

            // Audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            // Indexes
            $table->index('organization_id');

            $table->index('program_code');

            $table->index('program_type');

            $table->index('status');

            $table->index('starts_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};