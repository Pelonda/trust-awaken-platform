<?php

declare(strict_types=1);

namespace App\Core\Program\Actions;

use App\Core\Program\Models\Program;
use App\Core\Program\Repositories\ProgramRepositoryInterface;
use RuntimeException;

final readonly class RestoreProgram
{
    public function __construct(
        private ProgramRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): Program
    {
        $program = $this->repository->findTrashedByUuid($uuid);

        if ($program === null) {
            throw new RuntimeException('Program not found.');
        }

        $this->repository->restore($program);

        return $program->refresh();
    }
}