<?php

declare(strict_types=1);

namespace App\Core\Credential\Actions;

use App\Core\Credential\Models\Credential;
use App\Core\Credential\Repositories\CredentialRepositoryInterface;
use RuntimeException;

final readonly class RevokeCredential
{
    public function __construct(
        private CredentialRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): Credential
    {
        $credential = $this->repository->findByUuid($uuid);

        if ($credential === null) {
            throw new RuntimeException('Credential not found.');
        }

        return $this->repository->update(
            $credential,
            [
                'status' => 'revoked',
                'revoked_at' => now(),
            ]
        );
    }
}