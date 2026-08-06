<?php

declare(strict_types=1);

namespace App\Core\Identity\Repositories;

use App\Core\Identity\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UserRepository
{
    public function paginate(
        int $perPage = 15,
    ): LengthAwarePaginator {

        return User::query()
            ->with([
                'organization',
                'roles',
            ])
            ->latest()
            ->paginate($perPage);

    }

    public function findByUuid(
        string $uuid,
    ): User {

        return User::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

    }

    public function create(
        array $data,
    ): User {

        return User::create($data);

    }

    public function update(
        User $user,
        array $data,
    ): User {

        $user->update($data);

        return $user->refresh();

    }

    public function delete(
        User $user,
    ): void {

        $user->delete();

    }
}