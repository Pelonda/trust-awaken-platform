<?php

declare(strict_types=1);

namespace App\Core\Participant\Repositories;

use App\Core\Participant\DTOs\CreateParticipantData;
use App\Core\Participant\Models\Participant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ParticipantRepositoryInterface
{
    public function create(
        CreateParticipantData $data
    ): Participant;

    public function update(
        Participant $participant,
        array $attributes
    ): Participant;

    public function delete(
        Participant $participant
    ): void;

    public function restore(
        Participant $participant
    ): void;

    public function findByUuid(
        string $uuid
    ): ?Participant;

    public function findTrashedByUuid(
        string $uuid
    ): ?Participant;

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator;
}