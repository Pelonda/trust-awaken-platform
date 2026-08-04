<?php

declare(strict_types=1);

namespace App\Core\Credential\Actions;

use App\Core\Credential\Models\Credential;
use App\Core\Credential\Repositories\CredentialRepositoryInterface;
use RuntimeException;

final readonly class RestoreCredential
{
    public function __construct(
        private CredentialRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): Credential
    {
        $credential = $this->repository->findTrashedByUuid($uuid);

        if ($credential === null) {
            throw new RuntimeException('Credential not found.');
        }

        $this->repository->restore($credential);

        return $credential->refresh();
    }
}