<?php

declare(strict_types=1);

namespace App\Core\Participant\Actions;

use App\Core\Participant\Models\Participant;
use App\Core\Participant\Repositories\ParticipantRepositoryInterface;
use RuntimeException;

final readonly class RestoreParticipant
{
    public function __construct(
        private ParticipantRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): Participant
    {
        $participant = $this->repository->findTrashedByUuid($uuid);

        if ($participant === null) {
            throw new RuntimeException('Participant not found.');
        }

        $this->repository->restore($participant);

        return $participant->refresh();
    }
}