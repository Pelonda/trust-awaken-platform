<?php

declare(strict_types=1);

namespace Tests\Feature\Program;

use App\Core\Organization\Models\Organization;
use App\Core\Program\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class UpdateProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_existing_program(): void
    {
        $owner = User::factory()->create();

        $organization = Organization::query()->create([
            'uuid' => (string) Str::uuid(),
            'slug' => 'global-cybersafe',
            'display_name' => 'Global CyberSafe',
            'legal_name' => 'Global CyberSafe Foundation',
            'organization_type' => 'nonprofit',
            'status' => 'active',
            'owner_user_id' => $owner->id,
        ]);

        $program = Program::query()->create([
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

        $response = $this->putJson(
            "/api/v1/programs/{$program->uuid}",
            [
                'title' => 'Advanced Cybersecurity Bootcamp',
                'description' => 'Level 2',
                'program_type' => 'bootcamp',
                'delivery_mode' => 'hybrid',
            ]
        );

        $response->assertOk();

        $response->assertJsonFragment([
            'title' => 'Advanced Cybersecurity Bootcamp',
        ]);

        $this->assertDatabaseHas('programs', [
            'title' => 'Advanced Cybersecurity Bootcamp',
        ]);
    }

    public function test_update_unknown_program_returns_404(): void
    {
        $response = $this->putJson(
            '/api/v1/programs/00000000-0000-0000-0000-000000000000',
            [
                'title' => 'Test',
                'program_type' => 'bootcamp',
                'delivery_mode' => 'online',
            ]
        );

        $response->assertNotFound();
    }
}