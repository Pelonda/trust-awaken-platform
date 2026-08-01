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

            $table->uuid('uuid')
                ->unique()
                ->after('id');

            $table->string('user_type', 30)
                ->default('organization')
                ->after('password');

            $table->string('status', 30)
                ->default('active')
                ->after('user_type');

            $table->timestamp('last_login_at')
                ->nullable()
                ->after('remember_token');

            $table->softDeletes();

            $table->index('uuid');
            $table->index('user_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {

            $table->dropIndex(['uuid']);
            $table->dropIndex(['user_type']);
            $table->dropIndex(['status']);

            $table->dropSoftDeletes();

            $table->dropColumn([
                'uuid',
                'user_type',
                'status',
                'last_login_at',
            ]);
        });
    }
};