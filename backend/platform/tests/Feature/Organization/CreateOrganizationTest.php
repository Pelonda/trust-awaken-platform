<?php

declare(strict_types=1);

namespace Tests\Feature\Organization;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_organization_successfully(): void
    {
        $response = $this->postJson(
            '/api/v1/platform/organizations',
            [
                'display_name' => 'Global CyberSafe',
                'legal_name' => 'Global CyberSafe Foundation',
                'organization_type' => 'nonprofit',
            ]
        );

        $response->assertCreated();

        $response->assertJsonStructure([
            'uuid',
            'slug',
            'display_name',
            'legal_name',
            'organization_type',
            'status',
            'created_at',
            'updated_at',
        ]);

        $this->assertDatabaseHas('organizations', [
            'display_name' => 'Global CyberSafe',
            'legal_name' => 'Global CyberSafe Foundation',
            'organization_type' => 'nonprofit',
            'status' => 'draft',
        ]);
    }
}