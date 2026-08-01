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

            /*
            |--------------------------------------------------------------------------
            | Primary Identity
            |--------------------------------------------------------------------------
            */

            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('slug', 100)->unique();

            /*
            |--------------------------------------------------------------------------
            | Organization Identity
            |--------------------------------------------------------------------------
            */

            $table->string('display_name', 200);

            $table->string('legal_name', 255)->nullable();

            $table->string('organization_type', 50)
                ->default('company');

            $table->string('status', 30)
                ->default('draft');

            /*
            |--------------------------------------------------------------------------
            | Ownership
            |--------------------------------------------------------------------------
            */

            $table->foreignId('owner_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Lifecycle
            |--------------------------------------------------------------------------
            */

            $table->timestamp('activated_at')->nullable();

            $table->timestamp('archived_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('uuid');

            $table->index('slug');

            $table->index('organization_type');

            $table->index('status');

            $table->index('owner_user_id');

            $table->index([
                'status',
                'organization_type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};