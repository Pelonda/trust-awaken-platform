<?php

declare(strict_types=1);

namespace App\Core\Document\Services;

use App\Core\Document\Models\DocumentTemplate;
use App\Core\Document\Repositories\DocumentTemplateRepository;
use Illuminate\Support\Str;

final class DocumentTemplateService
{
    public function __construct(
        private readonly DocumentTemplateRepository $templates,
    ) {
    }

    public function create(
        array $data,
    ): DocumentTemplate {

        $data['uuid'] = (string) Str::uuid();

        $data['canvas'] ??= [];

        return $this->templates->create($data);

    }

    public function update(
        DocumentTemplate $template,
        array $data,
    ): DocumentTemplate {

        return $this->templates->update(
            $template,
            $data,
        );

    }
}