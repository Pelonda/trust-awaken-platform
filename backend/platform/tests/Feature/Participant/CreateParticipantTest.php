<?php

declare(strict_types=1);

namespace Tests\Feature\Participant;

use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class CreateParticipantTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_participant_successfully(): void
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
            '/api/v1/participants',
            [
                'organization_id' => $organization->id,
                'participant_code' => 'P-0001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '+243900000000',
                'date_of_birth' => '1995-05-10',
                'gender' => 'male',
                'country' => 'DR Congo',
            ]
        );

        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'participant_code',
                'first_name',
                'last_name',
                'email',
                'phone',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('participants', [
            'participant_code' => 'P-0001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'status' => 'active',
        ]);
    }
}