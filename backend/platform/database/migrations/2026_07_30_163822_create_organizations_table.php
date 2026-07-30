<?php

declare(strict_types=1);

use App\Core\Organization\Enums\OrganizationStatus;
use App\Core\Organization\Enums\OrganizationType;
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

            $table->string('slug')->unique();

            $table->string('display_name');

            $table->string('legal_name');

            $table->string('organization_type')
                ->default(OrganizationType::Company->value);

            $table->string('status')
                ->default(OrganizationStatus::Draft->value);

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