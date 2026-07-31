<?php

declare(strict_types=1);

namespace Tests\Feature\Organization;

use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UpdateOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_existing_organization(): void
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

        // Act
        $response = $this->putJson(
            "/api/v1/platform/organizations/{$organization->uuid}",
            [
                'display_name' => 'Global CyberSafe International',
                'legal_name' => 'Global CyberSafe Foundation International',
                'organization_type' => 'nonprofit',
            ]
        );

        // Assert
        $response->assertOk();

        $response->assertJsonFragment([
            'display_name' => 'Global CyberSafe International',
            'legal_name' => 'Global CyberSafe Foundation International',
        ]);

        $this->assertDatabaseHas('organizations', [
            'uuid' => $organization->uuid,
            'display_name' => 'Global CyberSafe International',
            'legal_name' => 'Global CyberSafe Foundation International',
        ]);
    }

    public function test_update_unknown_organization_returns_404(): void
    {
        // Act
        $response = $this->putJson(
            '/api/v1/platform/organizations/99999999-9999-9999-9999-999999999999',
            [
                'display_name' => 'Unknown',
                'legal_name' => 'Unknown',
                'organization_type' => 'company',
            ]
        );

        // Assert
        $response->assertNotFound();
    }
}