<?php

declare(strict_types=1);

namespace Tests\Feature\Participant;

use App\Core\Organization\Models\Organization;
use App\Core\Participant\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class UpdateParticipantTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_existing_participant(): void
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

        $participant = Participant::query()->create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'participant_code' => 'P-0001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'status' => 'active',
        ]);

        $response = $this->putJson(
            "/api/v1/participants/{$participant->uuid}",
            [
                'first_name' => 'Jonathan',
                'last_name' => 'Doe',
                'email' => 'jonathan@example.com',
                'phone' => '+243900000001',
                'gender' => 'male',
                'country' => 'DR Congo',
            ]
        );

        $response->assertOk();

        $response->assertJsonFragment([
            'first_name' => 'Jonathan',
        ]);

        $this->assertDatabaseHas('participants', [
            'first_name' => 'Jonathan',
            'email' => 'jonathan@example.com',
        ]);
    }

    public function test_update_unknown_participant_returns_404(): void
    {
        $response = $this->putJson(
            '/api/v1/participants/00000000-0000-0000-0000-000000000000',
            [
                'first_name' => 'Test',
                'last_name' => 'User',
            ]
        );

        $response->assertNotFound();
    }
}