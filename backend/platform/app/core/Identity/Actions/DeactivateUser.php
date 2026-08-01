<?php

declare(strict_types=1);

namespace App\Core\Identity\Actions;

use App\Models\User;
use App\Core\Identity\Repositories\UserRepositoryInterface;
use RuntimeException;

final readonly class DeactivateUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): User
    {
        $user = $this->repository->findByUuid($uuid);

        if ($user === null) {
            throw new RuntimeException('User not found.');
        }

        return $this->repository->update(
            $user,
            [
                'status' => 'suspended',
            ]
        );
    }
}