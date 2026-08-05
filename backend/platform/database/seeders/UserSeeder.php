<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [

            [
                'name' => 'Administrator',
                'email' => 'admin@awaken.org',
                'user_type' => 'organization',
            ],

            [
                'name' => 'Trainer',
                'email' => 'trainer@awaken.org',
                'user_type' => 'organization',
            ],

            [
                'name' => 'Operator',
                'email' => 'operator@awaken.org',
                'user_type' => 'organization',
            ],

            [
                'name' => 'Viewer',
                'email' => 'viewer@awaken.org',
                'user_type' => 'organization',
            ],

        ];

        foreach ($users as $user) {

            User::firstOrCreate(

                [
                    'email' => $user['email'],
                ],

                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'user_type' => $user['user_type'],
                    'status' => 'active',
                    'last_login_at' => null,
                ]

            );

        }
    }
}