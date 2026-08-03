<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\Repositories;

use App\Core\CredentialTemplate\DTOs\CreateCredentialTemplateData;
use App\Core\CredentialTemplate\Models\CredentialTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CredentialTemplateRepositoryInterface
{
    public function create(
        CreateCredentialTemplateData $data
    ): CredentialTemplate;

    public function update(
        CredentialTemplate $template,
        array $attributes
    ): CredentialTemplate;

    public function delete(
        CredentialTemplate $template
    ): void;

    public function restore(
        CredentialTemplate $template
    ): void;

    public function findByUuid(
        string $uuid
    ): ?CredentialTemplate;

    public function findTrashedByUuid(
        string $uuid
    ): ?CredentialTemplate;

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator;
}