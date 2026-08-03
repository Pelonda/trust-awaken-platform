<?php

declare(strict_types=1);

namespace Tests\Feature\Session;

use App\Core\Organization\Models\Organization;
use App\Core\Program\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class CreateSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_session_successfully(): void
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

        $response = $this->postJson('/api/v1/sessions', [
            'program_id' => $program->id,
            'session_code' => 'S-001',
            'title' => 'Day 1',
            'description' => 'Introduction',
            'session_number' => 1,
            'starts_at' => now()->toDateTimeString(),
            'ends_at' => now()->addHours(8)->toDateTimeString(),
            'venue' => 'Room A',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('program_sessions', [
            'session_code' => 'S-001',
            'title' => 'Day 1',
        ]);
    }
}