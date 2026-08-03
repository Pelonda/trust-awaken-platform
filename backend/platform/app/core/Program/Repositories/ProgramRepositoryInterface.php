<?php

declare(strict_types=1);

namespace App\Core\Program\Repositories;

use App\Core\Program\DTOs\CreateProgramData;
use App\Core\Program\Models\Program;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProgramRepositoryInterface
{
    public function create(CreateProgramData $data): Program;

    public function update(
        Program $program,
        array $attributes
    ): Program;

    public function delete(Program $program): void;

    public function restore(Program $program): void;

    public function findByUuid(string $uuid): ?Program;

    public function findTrashedByUuid(string $uuid): ?Program;

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator;
}