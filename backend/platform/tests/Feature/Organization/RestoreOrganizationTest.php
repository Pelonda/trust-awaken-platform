<?php

declare(strict_types=1);

namespace Tests\Feature\Organization;

use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RestoreOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_restore_archived_organization(): void
    {
        // Arrange
        User::factory()->create([
            'id' => 1,
        ]);

        $organization = Organization::query()->create([
            'uuid' => '11111111-1111-1111-1111-111111111111',
            'slug' => 'global-cybersafe',
            'display_name' => 'Global CyberSafe',
            'legal_name' => 'Global CyberSafe Foundation',
            'organization_type' => 'nonprofit',
            'status' => 'draft',
            'owner_user_id' => 1,
        ]);

        $organization->delete();

        $this->assertSoftDeleted('organizations', [
            'uuid' => $organization->uuid,
        ]);

        // Act
        $response = $this->postJson(
            "/api/v1/platform/organizations/{$organization->uuid}/restore"
        );

        // Assert
        $response->assertOk();

        $this->assertDatabaseHas('organizations', [
            'uuid' => $organization->uuid,
            'deleted_at' => null,
        ]);
    }

    public function test_restore_unknown_organization_returns_404(): void
    {
        $response = $this->postJson(
            '/api/v1/platform/organizations/99999999-9999-9999-9999-999999999999/restore'
        );

        $response->assertNotFound();
    }
}