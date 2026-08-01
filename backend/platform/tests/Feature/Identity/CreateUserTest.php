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
        $response = $this->postJson(
            '/api/v1/identity/users',
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]
        );

        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'email',
                'user_type',
                'status',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'status' => 'active',
            'user_type' => 'organization',
        ]);
    }
}