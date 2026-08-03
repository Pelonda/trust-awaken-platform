<?php

declare(strict_types=1);

namespace App\Core\Participant\Actions;

use App\Core\Participant\DTOs\CreateParticipantData;
use App\Core\Participant\Models\Participant;
use App\Core\Participant\Repositories\ParticipantRepositoryInterface;

final readonly class CreateParticipant
{
    public function __construct(
        private ParticipantRepositoryInterface $repository,
    ) {
    }

    public function execute(
        CreateParticipantData $data,
    ): Participant {

        return $this->repository->create($data);
    }
}