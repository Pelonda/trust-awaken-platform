<?php

declare(strict_types=1);

namespace App\Core\Program\Actions;

use App\Core\Program\DTOs\UpdateProgramData;
use App\Core\Program\Models\Program;
use App\Core\Program\Repositories\ProgramRepositoryInterface;
use RuntimeException;

final readonly class UpdateProgram
{
    public function __construct(
        private ProgramRepositoryInterface $repository,
    ) {
    }

    public function execute(
        string $uuid,
        UpdateProgramData $data,
    ): Program {

        $program = $this->repository->findByUuid($uuid);

        if ($program === null) {
            throw new RuntimeException('Program not found.');
        }

        return $this->repository->update(
            $program,
            [
                'title' => $data->title,
                'description' => $data->description,
                'program_type' => $data->programType,
                'delivery_mode' => $data->deliveryMode,
            ]
        );
    }
}