<?php

declare(strict_types=1);

namespace App\Core\Verification\Actions;

use App\Core\Credential\Repositories\CredentialRepositoryInterface;
use App\Core\Credential\Models\Credential;
use App\Core\Verification\Repositories\CredentialVerificationRepositoryInterface;
use RuntimeException;

final readonly class VerifyCredential
{
    public function __construct(
        private CredentialRepositoryInterface $credentials,
        private CredentialVerificationRepositoryInterface $verifications,
    ) {
    }

    public function execute(
        string $verificationCode,
        ?string $ipAddress,
        ?string $userAgent,
    ): Credential {

        $credential = $this->credentials->findByVerificationCode(
            $verificationCode
        );

        if ($credential === null) {
            throw new RuntimeException('Credential not found.');
        }

        $this->verifications->verify(
            $credential,
            $ipAddress,
            $userAgent,
        );

        return $credential;
    }
}