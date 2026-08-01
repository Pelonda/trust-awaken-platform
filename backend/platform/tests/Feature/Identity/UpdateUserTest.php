<?php

declare(strict_types=1);

namespace Tests\Feature\Identity;

use App\Core\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_existing_user(): void
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
        $response = $this->putJson(
            "/api/v1/identity/users/{$user->uuid}",
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
            ]
        );

        // Assert
        $response->assertOk();

        $response->assertJsonFragment([
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'uuid' => $user->uuid,
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
        ]);
    }

    public function test_update_unknown_user_returns_404(): void
    {
        $response = $this->putJson(
            '/api/v1/identity/users/99999999-9999-9999-9999-999999999999',
            [
                'name' => 'Unknown',
                'email' => 'unknown@example.com',
            ]
        );

        $response->assertNotFound();
    }
}