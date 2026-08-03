<?php

declare(strict_types=1);

namespace Tests\Feature\Participant;

use App\Core\Organization\Models\Organization;
use App\Core\Participant\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class RestoreParticipantTest extends TestCase
{
    use RefreshDatabase;

    public function test_restore_archived_participant(): void
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
            'status' => 'active',
        ]);

        $participant->delete();

        $response = $this->postJson(
            "/api/v1/participants/{$participant->uuid}/restore"
        );

        $response->assertOk();

        $this->assertDatabaseHas('participants', [
            'uuid' => $participant->uuid,
            'deleted_at' => null,
        ]);
    }

    public function test_restore_unknown_participant_returns_404(): void
    {
        $response = $this->postJson(
            '/api/v1/participants/00000000-0000-0000-0000-000000000000/restore'
        );

        $response->assertNotFound();
    }
}