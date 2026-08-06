<?php

declare(strict_types=1);

namespace App\Core\Document\Repositories;

use App\Core\Document\Models\DocumentTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class DocumentTemplateRepository
{
    public function paginate(
        int $perPage = 20,
    ): LengthAwarePaginator {

        return DocumentTemplate::query()
            ->with('elements')
            ->latest()
            ->paginate($perPage);

    }

    public function findByUuid(
        string $uuid,
    ): ?DocumentTemplate {

        return DocumentTemplate::query()
            ->where('uuid', $uuid)
            ->first();

    }

    public function create(
        array $data,
    ): DocumentTemplate {

        return DocumentTemplate::create($data);

    }

    public function update(
        DocumentTemplate $template,
        array $data,
    ): DocumentTemplate {

        $template->update($data);

        return $template->refresh();

    }

    public function delete(
        DocumentTemplate $template,
    ): void {

        $template->delete();

    }

}