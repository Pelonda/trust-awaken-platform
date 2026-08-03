<?php

declare(strict_types=1);

namespace Tests\Feature\Attendance;

use App\Core\Attendance\Models\Attendance;
use App\Core\Organization\Models\Organization;
use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use App\Core\Session\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class UpdateAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_existing_attendance(): void
    {
        $owner = User::factory()->create();

        $organization = Organization::create([
            'uuid' => (string) Str::uuid(),
            'slug' => 'global-cybersafe',
            'display_name' => 'Global CyberSafe',
            'legal_name' => 'Global CyberSafe Foundation',
            'organization_type' => 'nonprofit',
            'status' => 'active',
            'owner_user_id' => $owner->id,
        ]);

        $program = Program::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'program_code' => 'GC-001',
            'title' => 'Cybersecurity Bootcamp',
            'program_type' => 'bootcamp',
            'delivery_mode' => 'in_person',
            'status' => 'draft',
            'language' => 'en',
            'credential_enabled' => true,
        ]);

        $session = Session::create([
            'uuid' => (string) Str::uuid(),
            'program_id' => $program->id,
            'session_code' => 'S-001',
            'title' => 'Day 1',
            'session_number' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addHours(8),
            'status' => 'scheduled',
        ]);

        $participant = Participant::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'participant_code' => 'P-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'status' => 'active',
        ]);

        $attendance = Attendance::create([
            'uuid' => (string) Str::uuid(),
            'session_id' => $session->id,
            'participant_id' => $participant->id,
            'status' => 'present',
        ]);

        $response = $this->putJson(
            "/api/v1/attendance/{$attendance->uuid}",
            [
                'status' => 'late',
                'remarks' => 'Arrived 15 minutes late',
            ]
        );

        $response->assertOk();

        $response->assertJsonFragment([
            'status' => 'late',
        ]);

        $this->assertDatabaseHas('attendance', [
            'status' => 'late',
            'remarks' => 'Arrived 15 minutes late',
        ]);
    }

    public function test_update_unknown_attendance_returns_404(): void
    {
        $response = $this->putJson(
            '/api/v1/attendance/00000000-0000-0000-0000-000000000000',
            [
                'status' => 'late',
            ]
        );

        $response->assertNotFound();
    }
}