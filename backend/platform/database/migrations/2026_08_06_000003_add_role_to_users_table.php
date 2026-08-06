<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {

            $table->foreignId('organization_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();

            $table->string('phone', 30)
                ->nullable()
                ->after('email');

            $table->string('job_title', 120)
                ->nullable()
                ->after('phone');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {

            $table->dropConstrainedForeignId('organization_id');

            $table->dropColumn([
                'phone',
                'job_title',
            ]);

        });
    }
};