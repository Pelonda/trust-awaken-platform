<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('identity_users', function (Blueprint $table): void {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('name', 255);

            $table->string('email')->unique();

            $table->string('password');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamp('email_verified_at')
                ->nullable();

            $table->rememberToken();

            $table->timestamps();

            $table->softDeletes();

            $table->index('email');

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identity_users');
    }
};