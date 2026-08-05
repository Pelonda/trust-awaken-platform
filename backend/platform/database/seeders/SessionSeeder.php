<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Program\Models\Program;
use App\Core\Session\Models\Session;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class SessionSeeder extends Seeder
{
    public function run(): void
    {
        $programs = Program::all();

        foreach ($programs as $program) {

            for ($i = 1; $i <= 3; $i++) {

                Session::updateOrCreate(
                    [
                        'program_id' => $program->id,
                        'session_code' => sprintf(
                            'S-%03d-%02d',
                            $program->id,
                            $i
                        ),
                    ],
                    [
                        'uuid' => (string) Str::uuid(),
                        'program_id' => $program->id,
                        'session_code' => sprintf(
                            'S-%03d-%02d',
                            $program->id,
                            $i
                        ),
                        'title' => "Session {$i}",
                        'description' => "Training Session {$i}",
                        'session_number' => $i,
                        'starts_at' => now()->addDays($i),
                        'ends_at' => now()->addDays($i)->addHours(8),
                        'venue' => 'Main Campus',
                        'status' => 'scheduled',
                    ]
                );

            }

        }
    }
}