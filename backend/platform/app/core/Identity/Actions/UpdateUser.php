<?php

declare(strict_types=1);

namespace App\Core\Identity\Actions;

use App\Core\Identity\DTOs\UpdateUserData;
use App\Models\User;
use App\Core\Identity\Repositories\UserRepositoryInterface;
use RuntimeException;

final readonly class UpdateUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {
    }

    public function execute(
        string $uuid,
        UpdateUserData $data,
    ): User {
        $user = $this->repository->findByUuid($uuid);

        if ($user === null) {
            throw new RuntimeException('User not found.');
        }

        return $this->repository->update(
            $user,
            [
                'name' => $data->name,
                'email' => $data->email,
            ]
        );
    }
}