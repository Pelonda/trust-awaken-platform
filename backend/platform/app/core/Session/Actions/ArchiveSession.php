<?php

declare(strict_types=1);

namespace App\Core\Session\Actions;

use App\Core\Session\Repositories\SessionRepositoryInterface;
use RuntimeException;

final readonly class ArchiveSession
{
    public function __construct(
        private SessionRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): void
    {
        $session = $this->repository->findByUuid($uuid);

        if ($session === null) {
            throw new RuntimeException('Session not found.');
        }

        $this->repository->delete($session);
    }
}