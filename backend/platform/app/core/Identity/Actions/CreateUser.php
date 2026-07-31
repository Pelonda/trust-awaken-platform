<?php

declare(strict_types=1);

namespace App\Core\Identity\Actions;

use App\Core\Identity\DTOs\CreateUserData;
use App\Core\Identity\Models\User;
use App\Core\Identity\Repositories\UserRepositoryInterface;
use Illuminate\Support\Str;

final readonly class CreateUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {
    }

    public function execute(CreateUserData $data): User
    {
        $user = User::register(
            uuid: (string) Str::uuid(),
            name: $data->name,
            email: $data->email,
            password: $data->password,
        );

        return $this->repository->save($user);
    }
}