<?php

declare(strict_types=1);

namespace App\Core\Identity\Repositories;

use App\Core\Identity\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function update(User $user, array $attributes): User;

    public function delete(User $user): void;

    public function restore(User $user): void;

    public function findById(int $id): ?User;

    public function findByUuid(string $uuid): ?User;

    public function findByEmail(string $email): ?User;

    public function findTrashedByUuid(string $uuid): ?User;

    public function paginate(int $perPage = 15): LengthAwarePaginator;
}