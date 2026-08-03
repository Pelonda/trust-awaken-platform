<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\Actions;

use App\Core\CredentialTemplate\DTOs\UpdateCredentialTemplateData;
use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\CredentialTemplate\Repositories\CredentialTemplateRepositoryInterface;
use RuntimeException;

final readonly class UpdateCredentialTemplate
{
    public function __construct(
        private CredentialTemplateRepositoryInterface $repository,
    ) {
    }

    public function execute(
        string $uuid,
        UpdateCredentialTemplateData $data,
    ): CredentialTemplate {

        $template = $this->repository->findByUuid($uuid);

        if ($template === null) {
            throw new RuntimeException('Credential template not found.');
        }

        return $this->repository->update(
            $template,
            [
                'name' => $data->name,
                'paper_size' => $data->paperSize,
                'orientation' => $data->orientation,
                'is_default' => $data->isDefault,
            ]
        );
    }
}