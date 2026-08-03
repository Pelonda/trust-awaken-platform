<?php

declare(strict_types=1);

namespace App\Core\Session\Actions;

use App\Core\Session\DTOs\CreateSessionData;
use App\Core\Session\Models\Session;
use App\Core\Session\Repositories\SessionRepositoryInterface;

final readonly class CreateSession
{
    public function __construct(
        private SessionRepositoryInterface $repository,
    ) {
    }

    public function execute(
        CreateSessionData $data,
    ): Session {
        return $this->repository->create($data);
    }
}