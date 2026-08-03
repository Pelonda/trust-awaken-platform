<?php

declare(strict_types=1);

namespace App\Core\Session\Repositories;

use App\Core\Session\DTOs\CreateSessionData;
use App\Core\Session\Models\Session;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

final class EloquentSessionRepository implements SessionRepositoryInterface
{
    public function create(CreateSessionData $data): Session
    {
        return Session::create([
            'uuid' => (string) Str::uuid(),
            'program_id' => $data->programId,
            'session_code' => $data->sessionCode,
            'title' => $data->title,
            'description' => $data->description,
            'session_number' => $data->sessionNumber,
            'starts_at' => $data->startsAt,
            'ends_at' => $data->endsAt,
            'venue' => $data->venue,
            'status' => 'scheduled',
        ]);
    }

    public function update(
        Session $session,
        array $attributes
    ): Session {

        $session->update($attributes);

        return $session->refresh();
    }

    public function delete(Session $session): void
    {
        $session->delete();
    }

    public function restore(Session $session): void
    {
        $session->restore();
    }

    public function findByUuid(string $uuid): ?Session
    {
        return Session::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function findTrashedByUuid(string $uuid): ?Session
    {
        return Session::onlyTrashed()
            ->where('uuid', $uuid)
            ->first();
    }

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator {

        return Session::query()
            ->orderBy('session_number')
            ->paginate($perPage);
    }
}