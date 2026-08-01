<?php

declare(strict_types=1);

namespace Tests\Feature\Identity;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DeactivateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_deactivate_existing_user(): void
    {
        // Arrange
        $user = User::query()->create([
            'uuid'       => '11111111-1111-1111-1111-111111111111',
            'name'       => 'John Doe',
            'email'      => 'john@example.com',
            'password'   => bcrypt('Password123!'),
            'user_type'  => 'organization',
            'status'     => 'active',
        ]);

        // Act
        $response = $this->postJson(
            "/api/v1/identity/users/{$user->uuid}/deactivate"
        );

        // Assert
        $response->assertOk();

        $response->assertJsonFragment([
            'status' => 'suspended',
        ]);

        $this->assertDatabaseHas('users', [
            'uuid'   => $user->uuid,
            'status' => 'suspended',
        ]);
    }

    public function test_deactivate_unknown_user_returns_404(): void
    {
        $response = $this->postJson(
            '/api/v1/identity/users/99999999-9999-9999-9999-999999999999/deactivate'
        );

        $response->assertNotFound();
    }
}