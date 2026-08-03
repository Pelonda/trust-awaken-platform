<?php

declare(strict_types=1);

namespace App\Core\CredentialTemplate\Repositories;

use App\Core\CredentialTemplate\DTOs\CreateCredentialTemplateData;
use App\Core\CredentialTemplate\Models\CredentialTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

final class EloquentCredentialTemplateRepository implements CredentialTemplateRepositoryInterface
{
    public function create(
        CreateCredentialTemplateData $data
    ): CredentialTemplate {
        return CredentialTemplate::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $data->organizationId,
            'template_code' => $data->templateCode,
            'name' => $data->name,
            'credential_type' => $data->credentialType,
            'paper_size' => $data->paperSize,
            'orientation' => $data->orientation,
            'background_image' => $data->backgroundImage,
            'elements' => $data->elements,
            'is_default' => $data->isDefault,
        ]);
    }

    public function update(
        CredentialTemplate $template,
        array $attributes
    ): CredentialTemplate {
        $template->update($attributes);

        return $template->refresh();
    }

    public function delete(
        CredentialTemplate $template
    ): void {
        $template->delete();
    }

    public function restore(
        CredentialTemplate $template
    ): void {
        $template->restore();
    }

    public function findByUuid(
        string $uuid
    ): ?CredentialTemplate {
        return CredentialTemplate::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function findTrashedByUuid(
        string $uuid
    ): ?CredentialTemplate {
        return CredentialTemplate::onlyTrashed()
            ->where('uuid', $uuid)
            ->first();
    }

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator {
        return CredentialTemplate::query()
            ->orderBy('name')
            ->paginate($perPage);
    }
}