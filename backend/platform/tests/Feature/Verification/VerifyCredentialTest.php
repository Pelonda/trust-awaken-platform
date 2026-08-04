<?php

declare(strict_types=1);

namespace Tests\Feature\Verification;

use App\Core\Credential\Models\Credential;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class VerifyCredentialTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_existing_credential(): void
    {
        $credential = Credential::create([
            'uuid' => (string) Str::uuid(),
            'verification_code' => 'VERIFY-123456',
            'credential_number' => 'CR-000001',
            'credential_type' => 'certificate',
            'status' => 'issued',
            'issued_at' => now(),
        ]);

        $response = $this->getJson(
            '/api/v1/verify/VERIFY-123456'
        );

        $response->assertOk();

        $response->assertJsonFragment([
            'valid' => true,
            'credential_number' => 'CR-000001',
        ]);
    }

    public function test_verify_unknown_credential_returns_404(): void
    {
        $response = $this->getJson(
            '/api/v1/verify/UNKNOWN'
        );

        $response->assertNotFound();
    }
}