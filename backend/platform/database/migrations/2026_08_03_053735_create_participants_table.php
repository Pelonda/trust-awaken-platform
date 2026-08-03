<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table): void {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('participant_code', 50)->unique();

            $table->string('first_name', 100);

            $table->string('last_name', 100);

            $table->string('email')->nullable();

            $table->string('phone', 50)->nullable();

            $table->date('date_of_birth')->nullable();

            $table->string('gender', 30)->nullable();

            $table->string('country', 100)->nullable();

            $table->string('status', 30)
                ->default('active');

            $table->timestamps();

            $table->softDeletes();

            $table->index('organization_id');

            $table->index('participant_code');

            $table->index('email');

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};