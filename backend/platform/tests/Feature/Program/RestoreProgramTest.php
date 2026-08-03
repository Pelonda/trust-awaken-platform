<?php

declare(strict_types=1);

namespace Tests\Feature\Program;

use App\Core\Organization\Models\Organization;
use App\Core\Program\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class RestoreProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_restore_archived_program(): void
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

        $program->delete();

        $response = $this->postJson(
            "/api/v1/programs/{$program->uuid}/restore"
        );

        $response->assertOk();

        $this->assertDatabaseHas('programs', [
            'uuid' => $program->uuid,
            'deleted_at' => null,
        ]);
    }

    public function test_restore_unknown_program_returns_404(): void
    {
        $response = $this->postJson(
            '/api/v1/programs/00000000-0000-0000-0000-000000000000/restore'
        );

        $response->assertNotFound();
    }
}