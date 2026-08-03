<?php

declare(strict_types=1);

namespace App\Core\Participant\Actions;

use App\Core\Participant\Repositories\ParticipantRepositoryInterface;
use RuntimeException;

final readonly class ArchiveParticipant
{
    public function __construct(
        private ParticipantRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): void
    {
        $participant = $this->repository->findByUuid($uuid);

        if ($participant === null) {
            throw new RuntimeException('Participant not found.');
        }

        $this->repository->delete($participant);
    }
}