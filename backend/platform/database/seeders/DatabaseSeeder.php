<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        OrganizationSeeder::class,
        UserSeeder::class,
        RoleSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,
        UserRoleSeeder::class,
        ProgramSeeder::class,
        ParticipantSeeder::class,
        SessionSeeder::class,
        AttendanceSeeder::class,
        CredentialTemplateSeeder::class,
        CredentialSeeder::class,
    ]);
}
}