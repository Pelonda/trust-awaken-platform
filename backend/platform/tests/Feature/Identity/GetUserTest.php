<?php

declare(strict_types=1);

namespace Tests\Feature\Identity;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class GetUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_existing_user(): void
    {
        // Arrange
        $user = User::query()->create([
            'uuid' => '11111111-1111-1111-1111-111111111111',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('Password123!'),
            'is_active' => true,
        ]);

        // Act
        $response = $this->getJson(
            "/api/v1/identity/users/{$user->uuid}"
        );

        // Assert
        $response->assertOk();

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

        $response->assertJsonFragment([
            'uuid' => '11111111-1111-1111-1111-111111111111',
            'name' => 'John Doe',
        ]);
    }

    public function test_get_unknown_user_returns_404(): void
    {
        $response = $this->getJson(
            '/api/v1/identity/users/99999999-9999-9999-9999-999999999999'
        );

        $response->assertNotFound();
    }
}