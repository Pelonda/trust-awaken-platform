<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table): void {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('session_id')
                ->constrained('program_sessions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('participant_id')
                ->constrained('participants')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('status', 30)
                ->default('present');

            $table->timestamp('checked_in_at')->nullable();

            $table->timestamp('checked_out_at')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->unique([
                'session_id',
                'participant_id',
            ]);

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};