<?php

declare(strict_types=1);

namespace App\Core\Credential\Actions;

use App\Core\Credential\DTOs\CreateCredentialData;
use App\Core\Credential\Models\Credential;
use App\Core\Credential\Repositories\CredentialRepositoryInterface;

final readonly class CreateCredential
{
    public function __construct(
        private CredentialRepositoryInterface $repository,
    ) {
    }

    public function execute(
        CreateCredentialData $data,
    ): Credential {
        return $this->repository->create($data);
    }
}