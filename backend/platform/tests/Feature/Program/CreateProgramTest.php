<?php

declare(strict_types=1);

namespace Tests\Feature\Program;

use App\Models\User;
use App\Core\Organization\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class CreateProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_program_successfully(): void
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

        $response = $this->postJson(
            '/api/v1/programs',
            [
                'organization_id' => $organization->id,
                'program_code' => 'GC-001',
                'title' => 'Cybersecurity Bootcamp',
                'description' => 'Level 1',
                'program_type' => 'bootcamp',
                'delivery_mode' => 'in_person',
                'starts_at' => now()->toDateTimeString(),
                'ends_at' => now()->addDays(5)->toDateTimeString(),
                'venue' => 'Kinshasa',
                'capacity' => 100,
                'language' => 'en',
                'credential_enabled' => true,
                'created_by' => $owner->id,
            ]
        );

        $response->assertCreated();

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

        $this->assertDatabaseHas('programs', [
            'program_code' => 'GC-001',
            'title' => 'Cybersecurity Bootcamp',
            'status' => 'draft',
        ]);
    }
}