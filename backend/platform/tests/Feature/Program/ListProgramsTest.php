<?php

declare(strict_types=1);

namespace Tests\Feature\Program;

use App\Core\Organization\Models\Organization;
use App\Core\Program\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class ListProgramsTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_programs(): void
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

        Program::query()->create([
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

        Program::query()->create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'program_code' => 'GC-002',
            'title' => 'AI Fundamentals',
            'program_type' => 'course',
            'delivery_mode' => 'online',
            'status' => 'draft',
            'language' => 'en',
            'credential_enabled' => true,
        ]);

        $response = $this->getJson('/api/v1/programs');

        $response->assertOk();

        $response->assertJsonCount(2, 'data');
    }
}