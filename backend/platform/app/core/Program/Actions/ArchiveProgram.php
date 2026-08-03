<?php

declare(strict_types=1);

namespace App\Core\Program\Actions;

use App\Core\Program\Repositories\ProgramRepositoryInterface;
use RuntimeException;

final readonly class ArchiveProgram
{
    public function __construct(
        private ProgramRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): void
    {
        $program = $this->repository->findByUuid($uuid);

        if ($program === null) {
            throw new RuntimeException('Program not found.');
        }

        $this->repository->delete($program);
    }
}