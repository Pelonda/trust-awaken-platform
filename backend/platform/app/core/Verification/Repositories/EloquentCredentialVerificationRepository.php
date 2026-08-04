<?php

declare(strict_types=1);

namespace App\Core\Verification\Repositories;

use App\Core\Credential\Models\Credential;
use App\Core\Verification\Models\CredentialVerification;
use Illuminate\Support\Str;

final class EloquentCredentialVerificationRepository implements CredentialVerificationRepositoryInterface
{
    public function verify(
        Credential $credential,
        ?string $ipAddress,
        ?string $userAgent,
    ): CredentialVerification {

        return CredentialVerification::create([
            'uuid' => (string) Str::uuid(),
            'credential_id' => $credential->id,
            'verification_code' => $credential->verification_code,
            'valid' => $credential->status === 'issued',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'verified_at' => now(),
        ]);
    }
}