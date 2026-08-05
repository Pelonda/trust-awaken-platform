<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Attendance\Models\Attendance;
use App\Core\Participant\Models\Participant;
use App\Core\Session\Models\Session;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = Session::all();
        $participants = Participant::take(10)->get();

        foreach ($sessions as $session) {

            foreach ($participants as $participant) {

                Attendance::updateOrCreate(
                    [
                        'session_id' => $session->id,
                        'participant_id' => $participant->id,
                    ],
                    [
                        'uuid' => (string) Str::uuid(),
                        'status' => 'present',
                        'checked_in_at' => now(),
                        'remarks' => null,
                    ]
                );

            }

        }
    }
}