<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\Actions;

use App\Core\CredentialTemplate\DTOs\CreateCredentialTemplateData;
use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\CredentialTemplate\Repositories\CredentialTemplateRepositoryInterface;

final readonly class CreateCredentialTemplate
{
    public function __construct(
        private CredentialTemplateRepositoryInterface $repository,
    ) {
    }

    public function execute(
        CreateCredentialTemplateData $data,
    ): CredentialTemplate {

        return $this->repository->create($data);
    }
}