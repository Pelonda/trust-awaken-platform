<?php

declare(strict_types=1);

namespace Tests\Feature\Program;

use App\Core\Organization\Models\Organization;
use App\Core\Program\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class GetProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_existing_program(): void
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
            'description' => 'Level 1',
            'program_type' => 'bootcamp',
            'delivery_mode' => 'in_person',
            'status' => 'draft',
            'language' => 'en',
            'credential_enabled' => true,
        ]);

        $response = $this->getJson(
            "/api/v1/programs/{$program->uuid}"
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'program_code',
                'title',
                'program_type',
                'delivery_mode',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_get_unknown_program_returns_404(): void
    {
        $response = $this->getJson(
            '/api/v1/programs/00000000-0000-0000-0000-000000000000'
        );

        $response->assertNotFound();
    }
}