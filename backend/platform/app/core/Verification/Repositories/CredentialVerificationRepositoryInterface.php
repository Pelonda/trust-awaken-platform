<?php

declare(strict_types=1);

namespace App\Core\Verification\Repositories;

use App\Core\Credential\Models\Credential;
use App\Core\Verification\Models\CredentialVerification;

interface CredentialVerificationRepositoryInterface
{
    public function verify(
        Credential $credential,
        ?string $ipAddress,
        ?string $userAgent,
    ): CredentialVerification;
    
}