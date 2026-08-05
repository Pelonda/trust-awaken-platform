<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            'dashboard.view',

            'organization.view',
            'organization.create',
            'organization.update',
            'organization.delete',

            'user.view',
            'user.create',
            'user.update',
            'user.delete',

            'program.view',
            'program.create',
            'program.update',
            'program.delete',

            'participant.view',
            'participant.create',
            'participant.update',
            'participant.delete',

            'session.view',
            'session.create',
            'session.update',
            'session.delete',

            'attendance.view',
            'attendance.create',
            'attendance.update',
            'attendance.delete',

            'credential.view',
            'credential.create',
            'credential.update',
            'credential.delete',

            'verification.public',

        ];

        foreach ($permissions as $permission) {

            DB::table('permissions')->updateOrInsert(

                [
                    'name' => $permission,
                ],

                [
                    'uuid' => (string) Str::uuid(),
                    'display_name' => ucwords(
                        str_replace(
                            '.',
                            ' ',
                            $permission
                        )
                    ),
                    'description' => null,
                    'system' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]

            );

        }
    }
}