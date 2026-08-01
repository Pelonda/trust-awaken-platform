<?php

declare(strict_types=1);

namespace Tests\Feature\Identity;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_users(): void
    {
        // Arrange
        User::query()->create([
            'uuid' => '11111111-1111-1111-1111-111111111111',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('Password123!'),
            'is_active' => true,
        ]);

        User::query()->create([
            'uuid' => '22222222-2222-2222-2222-222222222222',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('Password123!'),
            'is_active' => true,
        ]);

        // Act
        $response = $this->getJson('/api/v1/identity/users');

        // Assert
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                    'email',
                    'is_active',
                    'email_verified_at',
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