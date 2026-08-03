<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\Actions;

use App\Core\CredentialTemplate\Repositories\CredentialTemplateRepositoryInterface;
use RuntimeException;

final readonly class ArchiveCredentialTemplate
{
    public function __construct(
        private CredentialTemplateRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): void
    {
        $template = $this->repository->findByUuid($uuid);

        if ($template === null) {
            throw new RuntimeException('Credential template not found.');
        }

        $this->repository->delete($template);
    }
}