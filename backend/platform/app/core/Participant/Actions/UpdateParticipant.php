<?php

declare(strict_types=1);

namespace App\Core\Participant\Actions;

use App\Core\Participant\DTOs\UpdateParticipantData;
use App\Core\Participant\Models\Participant;
use App\Core\Participant\Repositories\ParticipantRepositoryInterface;
use RuntimeException;

final readonly class UpdateParticipant
{
    public function __construct(
        private ParticipantRepositoryInterface $repository,
    ) {
    }

    public function execute(
        string $uuid,
        UpdateParticipantData $data,
    ): Participant {

        $participant = $this->repository->findByUuid($uuid);

        if ($participant === null) {
            throw new RuntimeException('Participant not found.');
        }

        return $this->repository->update(
            $participant,
            [
                'first_name' => $data->firstName,
                'last_name' => $data->lastName,
                'email' => $data->email,
                'phone' => $data->phone,
                'gender' => $data->gender,
                'country' => $data->country,
            ]
        );
    }
}