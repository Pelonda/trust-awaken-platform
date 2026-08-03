<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credentials', function (Blueprint $table): void {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('participant_id')
                ->constrained('participants')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('program_id')
                ->constrained('programs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('session_id')
                ->constrained('program_sessions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('template_id')
                ->constrained('credential_templates')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('credential_number', 100)->unique();

            $table->string('verification_code', 100)->unique();

            $table->string('credential_type', 50);

            $table->string('status', 30)
                ->default('draft');

            $table->timestamp('issued_at')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->timestamp('revoked_at')->nullable();

            $table->string('pdf_path')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('participant_id');
            $table->index('credential_number');
            $table->index('verification_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};