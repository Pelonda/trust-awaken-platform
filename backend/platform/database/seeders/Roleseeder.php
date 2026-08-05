<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

final class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [

            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full platform access',
                'system' => true,
            ],

            [
                'name' => 'organization_admin',
                'display_name' => 'Organization Administrator',
                'description' => 'Manage one organization',
                'system' => true,
            ],

            [
                'name' => 'trainer',
                'display_name' => 'Trainer',
                'description' => 'Manage training',
                'system' => true,
            ],

            [
                'name' => 'operator',
                'display_name' => 'Operator',
                'description' => 'Daily operations',
                'system' => true,
            ],

            [
                'name' => 'viewer',
                'display_name' => 'Viewer',
                'description' => 'Read only',
                'system' => true,
            ],

        ];

        foreach ($roles as $role) {

            DB::table('roles')->updateOrInsert(

                [
                    'name' => $role['name'],
                ],

                [
                    'uuid' => (string) Str::uuid(),
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                    'system' => $role['system'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]

            );

        }
    }
}