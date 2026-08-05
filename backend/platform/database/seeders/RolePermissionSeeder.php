<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = DB::table('roles')
            ->pluck('id', 'name');

        $permissions = DB::table('permissions')
            ->pluck('id', 'name');

        $map = [

            'super_admin' => [
                '*',
            ],

            'organization_admin' => [
                'dashboard.view',

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
            ],

            'trainer' => [
                'dashboard.view',

                'program.view',

                'participant.view',

                'session.view',

                'attendance.view',
                'attendance.create',

                'credential.view',
            ],

            'operator' => [
                'dashboard.view',

                'participant.view',
                'participant.create',

                'attendance.view',
                'attendance.create',

                'credential.view',
            ],

            'viewer' => [
                'dashboard.view',

                'program.view',

                'participant.view',

                'session.view',

                'attendance.view',

                'credential.view',
            ],

        ];

        foreach ($map as $role => $list) {

            if ($list === ['*']) {

                foreach ($permissions as $permissionId) {

                    DB::table('permission_role')
                        ->updateOrInsert(
                            [
                                'role_id' => $roles[$role],
                                'permission_id' => $permissionId,
                            ],
                            [
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                }

                continue;

            }

            foreach ($list as $permission) {

                DB::table('permission_role')
                    ->updateOrInsert(
                        [
                            'role_id' => $roles[$role],
                            'permission_id' => $permissions[$permission],
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

            }

        }
    }
}