<?php

declare(strict_types=1);

namespace App\Core\Session\Actions;

use App\Core\Session\Models\Session;
use App\Core\Session\Repositories\SessionRepositoryInterface;
use RuntimeException;

final readonly class RestoreSession
{
    public function __construct(
        private SessionRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): Session
    {
        $session = $this->repository->findTrashedByUuid($uuid);

        if ($session === null) {
            throw new RuntimeException('Session not found.');
        }

        $this->repository->restore($session);

        return $session->refresh();
    }
}