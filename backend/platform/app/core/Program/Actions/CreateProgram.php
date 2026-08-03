<?php

declare(strict_types=1);

namespace App\Core\Program\Actions;

use App\Core\Program\DTOs\CreateProgramData;
use App\Core\Program\Models\Program;
use App\Core\Program\Repositories\ProgramRepositoryInterface;

final readonly class CreateProgram
{
    public function __construct(
        private ProgramRepositoryInterface $repository,
    ) {
    }

    public function execute(
        CreateProgramData $data,
    ): Program {

        return $this->repository->create($data);
    }
}