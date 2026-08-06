<?php

declare(strict_types=1);

namespace App\Core\Identity\Services;

use App\Core\Identity\Models\User;
use App\Core\Identity\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserService
{
    public function __construct(
        private readonly UserRepository $users,
    ) {
    }

    public function create(
        array $data,
    ): User {

        $data['uuid'] = (string) Str::uuid();

        $data['password'] = Hash::make(
            $data['password']
        );

        return $this->users->create($data);

    }

    public function update(
        User $user,
        array $data,
    ): User {

        if (
            isset($data['password']) &&
            $data['password']
        ) {

            $data['password'] = Hash::make(
                $data['password']
            );

        } else {

            unset($data['password']);

        }

        return $this->users->update(
            $user,
            $data
        );

    }
}