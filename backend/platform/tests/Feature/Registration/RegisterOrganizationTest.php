<?php

declare(strict_types=1);

namespace Tests\Feature\Registration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegisterOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_endpoint_exists(): void
    {
        // Act
        $response = $this->postJson(
            '/api/v1/public/register',
            [
                'organization_name'      => 'Global CyberSafe',
                'legal_name'             => 'Global CyberSafe Foundation',
                'organization_type'      => 'nonprofit',
                'owner_name'             => 'John Doe',
                'owner_email'            => 'john@example.com',
                'password'               => 'Password123!',
                'password_confirmation'  => 'Password123!',
            ]
        );

        // Assert
        $response->assertCreated();

        $response->assertJsonStructure([
            'message',
            'user_uuid',
            'organization_uuid',
            'token',
        ]);

        $response->assertJson([
            'message' => 'Registration completed successfully.',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('organizations', [
            'display_name' => 'Global CyberSafe',
        ]);

        $this->assertDatabaseCount('users', 1);

$this->assertDatabaseCount('organizations', 1);
        
    }
}