<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\Actions;

use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\CredentialTemplate\Repositories\CredentialTemplateRepositoryInterface;
use RuntimeException;

final readonly class RestoreCredentialTemplate
{
    public function __construct(
        private CredentialTemplateRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): CredentialTemplate
    {
        $template = $this->repository->findTrashedByUuid($uuid);

        if ($template === null) {
            throw new RuntimeException('Credential template not found.');
        }

        $this->repository->restore($template);

        return $template->refresh();
    }
}