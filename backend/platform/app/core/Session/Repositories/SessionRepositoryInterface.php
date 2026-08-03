<?php

declare(strict_types=1);

namespace App\Core\Session\Repositories;

use App\Core\Session\DTOs\CreateSessionData;
use App\Core\Session\Models\Session;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SessionRepositoryInterface
{
    /**
     * Create a new session.
     */
    public function create(
        CreateSessionData $data
    ): Session;

    /**
     * Update an existing session.
     */
    public function update(
        Session $session,
        array $attributes
    ): Session;

    /**
     * Archive (soft delete) a session.
     */
    public function delete(
        Session $session
    ): void;

    /**
     * Restore an archived session.
     */
    public function restore(
        Session $session
    ): void;

    /**
     * Find a session by UUID.
     */
    public function findByUuid(
        string $uuid
    ): ?Session;

    /**
     * Find an archived session by UUID.
     */
    public function findTrashedByUuid(
        string $uuid
    ): ?Session;

    /**
     * Paginate sessions.
     */
    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator;
}