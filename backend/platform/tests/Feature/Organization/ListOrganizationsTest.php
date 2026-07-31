<?php

declare(strict_types=1);

namespace Tests\Feature\Organization;

use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ListOrganizationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_organizations(): void
    {
        // Arrange
        User::factory()->create([
            'id' => 1,
        ]);

        Organization::query()->create([
            'uuid' => '11111111-1111-1111-1111-111111111111',
            'slug' => 'global-cybersafe',
            'display_name' => 'Global CyberSafe',
            'legal_name' => 'Global CyberSafe Foundation',
            'organization_type' => 'nonprofit',
            'status' => 'draft',
            'owner_user_id' => 1,
        ]);

        Organization::query()->create([
            'uuid' => '22222222-2222-2222-2222-222222222222',
            'slug' => 'awtheos',
            'display_name' => 'AWTheos',
            'legal_name' => 'AWTheos LLC',
            'organization_type' => 'company',
            'status' => 'active',
            'owner_user_id' => 1,
        ]);

        // Act
        $response = $this->getJson('/api/v1/platform/organizations');

        // Assert
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'slug',
                    'display_name',
                    'legal_name',
                    'organization_type',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ],
            'links',
            'meta',
        ]);

        $response->assertJsonCount(2, 'data');
    }
}