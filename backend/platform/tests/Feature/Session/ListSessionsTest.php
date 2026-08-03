<?php

declare(strict_types=1);

namespace Tests\Feature\Session;

use App\Core\Organization\Models\Organization;
use App\Core\Program\Models\Program;
use App\Core\Session\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class ListSessionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_sessions(): void
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

        Session::create([
            'uuid' => (string) Str::uuid(),
            'program_id' => $program->id,
            'session_code' => 'S-001',
            'title' => 'Day 1',
            'session_number' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addHours(8),
            'status' => 'scheduled',
        ]);

        Session::create([
            'uuid' => (string) Str::uuid(),
            'program_id' => $program->id,
            'session_code' => 'S-002',
            'title' => 'Day 2',
            'session_number' => 2,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(8),
            'status' => 'scheduled',
        ]);

        $response = $this->getJson('/api/v1/sessions');

        $response->assertOk();

        $response->assertJsonCount(2, 'data');
    }
}