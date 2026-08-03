<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('program_sessions', function (Blueprint $table): void {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('program_id')
                ->constrained('programs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('session_code', 50)->unique();

            $table->string('title', 255);

            $table->text('description')->nullable();

            $table->unsignedInteger('session_number');

            $table->timestamp('starts_at');

            $table->timestamp('ends_at');

            $table->string('venue', 255)->nullable();

            $table->string('status', 30)
                ->default('scheduled');

            $table->timestamps();

            $table->softDeletes();

            $table->index('program_id');
            $table->index('session_code');
            $table->index('status');
            $table->index('starts_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_sessions');
    }
};