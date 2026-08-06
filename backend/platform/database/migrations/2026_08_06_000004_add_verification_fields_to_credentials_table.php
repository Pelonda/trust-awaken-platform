<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credentials', function (Blueprint $table): void {

            if (!Schema::hasColumn('credentials', 'verification_url')) {

                $table->string('verification_url')
                    ->nullable()
                    ->after('verification_code');

            }

            if (!Schema::hasColumn('credentials', 'qr_code_path')) {

                $table->string('qr_code_path')
                    ->nullable()
                    ->after('verification_url');

            }

        });
    }

    public function down(): void
    {
        Schema::table('credentials', function (Blueprint $table): void {

            if (Schema::hasColumn('credentials', 'verification_url')) {
                $table->dropColumn('verification_url');
            }

            if (Schema::hasColumn('credentials', 'qr_code_path')) {
                $table->dropColumn('qr_code_path');
            }

        });
    }
};