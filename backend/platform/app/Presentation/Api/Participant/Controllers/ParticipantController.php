<?php

declare(strict_types=1);

namespace App\Presentation\Api\Participant\Controllers;

use App\Core\Participant\Actions\CreateParticipant;
use App\Core\Participant\DTOs\CreateParticipantData;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Participant\Requests\StoreParticipantRequest;
use App\Presentation\Api\Participant\Resources\ParticipantResource;
use App\Core\Participant\Repositories\ParticipantRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Core\Participant\Actions\UpdateParticipant;
use App\Core\Participant\DTOs\UpdateParticipantData;
use App\Presentation\Api\Participant\Requests\UpdateParticipantRequest;
use RuntimeException;
use App\Core\Participant\Actions\ArchiveParticipant;
use App\Core\Participant\Actions\RestoreParticipant;

final class ParticipantController extends Controller
{
    public function __construct(
        private readonly CreateParticipant $createParticipant,
        private readonly ParticipantRepositoryInterface $repository,
        private readonly UpdateParticipant $updateParticipant,
        private readonly ArchiveParticipant $archiveParticipant,
        private readonly RestoreParticipant $restoreParticipant,
    ) {
    }

    public function store(
        StoreParticipantRequest $request,
    ): JsonResponse {

        $participant = $this->createParticipant->execute(
            new CreateParticipantData(
                organizationId: $request->integer('organization_id'),
                participantCode: $request->string('participant_code')->toString(),
                firstName: $request->string('first_name')->toString(),
                lastName: $request->string('last_name')->toString(),
                email: $request->input('email'),
                phone: $request->input('phone'),
                dateOfBirth: $request->input('date_of_birth'),
                gender: $request->input('gender'),
                country: $request->input('country'),
            )
        );

        return (new ParticipantResource($participant))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): JsonResponse
{
    $participant = $this->repository->findByUuid($uuid);

    if ($participant === null) {
        return response()->json([
            'message' => 'Participant not found.',
        ], 404);
    }

    return (new ParticipantResource($participant))
        ->response()
        ->setStatusCode(200);
}

public function index(): JsonResponse
{
    return ParticipantResource::collection(
        $this->repository->paginate()
    )->response();
}

public function update(
    UpdateParticipantRequest $request,
    string $uuid,
): JsonResponse {

    try {

        $participant = $this->updateParticipant->execute(
            uuid: $uuid,
            data: new UpdateParticipantData(
                firstName: $request->string('first_name')->toString(),
                lastName: $request->string('last_name')->toString(),
                email: $request->input('email'),
                phone: $request->input('phone'),
                gender: $request->input('gender'),
                country: $request->input('country'),
            )
        );

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Participant not found.',
        ], 404);

    }

    return (new ParticipantResource($participant))
        ->response()
        ->setStatusCode(200);
}

public function destroy(string $uuid): JsonResponse
{
    try {

        $this->archiveParticipant->execute($uuid);

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Participant not found.',
        ], 404);

    }

    return response()->json([], 204);
}

public function restore(string $uuid): JsonResponse
{
    try {

        $participant = $this->restoreParticipant->execute($uuid);

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Participant not found.',
        ], 404);

    }

    return (new ParticipantResource($participant))
        ->response()
        ->setStatusCode(200);
}

}

