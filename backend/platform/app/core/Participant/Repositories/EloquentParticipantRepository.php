<?php

declare(strict_types=1);

namespace App\Core\Participant\Repositories;

use App\Core\Participant\DTOs\CreateParticipantData;
use App\Core\Participant\Models\Participant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

final class EloquentParticipantRepository implements ParticipantRepositoryInterface
{
    public function create(
        CreateParticipantData $data
    ): Participant {

        return Participant::create([

            'uuid' => (string) Str::uuid(),

            'organization_id' => $data->organizationId,

            'participant_code' => $data->participantCode,

            'first_name' => $data->firstName,

            'last_name' => $data->lastName,

            'email' => $data->email,

            'phone' => $data->phone,

            'date_of_birth' => $data->dateOfBirth,

            'gender' => $data->gender,

            'country' => $data->country,

            'status' => 'active',
        ]);
    }

    public function update(
        Participant $participant,
        array $attributes
    ): Participant {

        $participant->update($attributes);

        return $participant->refresh();
    }

    public function delete(
        Participant $participant
    ): void {

        $participant->delete();
    }

    public function restore(
        Participant $participant
    ): void {

        $participant->restore();
    }

    public function findByUuid(
        string $uuid
    ): ?Participant {

        return Participant::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function findTrashedByUuid(
        string $uuid
    ): ?Participant {

        return Participant::onlyTrashed()
            ->where('uuid', $uuid)
            ->first();
    }

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator {

        return Participant::query()
            ->orderBy('last_name')
            ->paginate($perPage);
    }
    
}