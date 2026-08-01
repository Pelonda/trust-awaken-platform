<?php

declare(strict_types=1);

namespace App\Core\Identity\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class EloquentUserRepository implements UserRepositoryInterface
{
    public function save(User $user): User
    {
        $user->save();

        return $user->refresh();
    }

    public function update(User $user, array $attributes): User
    {
        $user->update($attributes);

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function restore(User $user): void
    {
        $user->restore();
    }

    public function findById(int $id): ?User
    {
        return User::query()->find($id);
    }

    public function findByUuid(string $uuid): ?User
    {
        return User::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }

    public function findTrashedByUuid(string $uuid): ?User
    {
        return User::onlyTrashed()
            ->where('uuid', $uuid)
            ->first();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->orderBy('name')
            ->paginate($perPage);
    }
}