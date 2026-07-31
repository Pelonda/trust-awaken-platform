<?php

declare(strict_types=1);

namespace Tests\Feature\Organization;

use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class GetOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_existing_organization(): void
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
        $response = $this->getJson(
            "/api/v1/platform/organizations/{$organization->uuid}"
        );

        // Assert
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'slug',
                'display_name',
                'legal_name',
                'organization_type',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);

        $response->assertJsonFragment([
            'uuid' => '11111111-1111-1111-1111-111111111111',
            'display_name' => 'Global CyberSafe',
        ]);
    }

    public function test_get_unknown_organization_returns_404(): void
    {
        // Act
        $response = $this->getJson(
            '/api/v1/platform/organizations/99999999-9999-9999-9999-999999999999'
        );

        // Assert
        $response->assertNotFound();
    }
}