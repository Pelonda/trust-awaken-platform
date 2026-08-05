<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = DB::table('roles')
            ->pluck('id', 'name');

        $users = User::query()
            ->pluck('id', 'email');

        $assignments = [

            'admin@awaken.org' => 'super_admin',

            'trainer@awaken.org' => 'trainer',

            'operator@awaken.org' => 'operator',

            'viewer@awaken.org' => 'viewer',

        ];

        foreach ($assignments as $email => $role) {

            if (
                ! isset($users[$email]) ||
                ! isset($roles[$role])
            ) {
                continue;
            }

            DB::table('role_user')->updateOrInsert(
                [
                    'user_id' => $users[$email],
                    'role_id' => $roles[$role],
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

        }
    }
}