<?php

declare(strict_types=1);

namespace App\Core\Session\Actions;

use App\Core\Session\DTOs\UpdateSessionData;
use App\Core\Session\Models\Session;
use App\Core\Session\Repositories\SessionRepositoryInterface;
use RuntimeException;

final readonly class UpdateSession
{
    public function __construct(
        private SessionRepositoryInterface $repository,
    ) {
    }

    public function execute(
        string $uuid,
        UpdateSessionData $data,
    ): Session {

        $session = $this->repository->findByUuid($uuid);

        if ($session === null) {
            throw new RuntimeException('Session not found.');
        }

        return $this->repository->update(
            $session,
            [
                'title' => $data->title,
                'description' => $data->description,
            ]
        );
    }
}