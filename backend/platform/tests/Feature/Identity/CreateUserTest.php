<?php

declare(strict_types=1);

namespace Tests\Feature\Identity;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_successfully(): void
    {
        // Act
        $response = $this->postJson(
            '/api/v1/identity/users',
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]
        );

        // Assert
        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'email',
                'is_active',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'is_active' => true,
        ]);
    }
}