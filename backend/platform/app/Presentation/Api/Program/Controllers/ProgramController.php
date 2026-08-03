<?php

declare(strict_types=1);

namespace App\Presentation\Api\Program\Controllers;

use App\Core\Program\Actions\CreateProgram;
use App\Core\Program\DTOs\CreateProgramData;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Program\Requests\StoreProgramRequest;
use App\Presentation\Api\Program\Resources\ProgramResource;
use App\Core\Program\Repositories\ProgramRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Core\Program\Actions\UpdateProgram;
use App\Core\Program\DTOs\UpdateProgramData;
use App\Presentation\Api\Program\Requests\UpdateProgramRequest;
use RuntimeException;
use App\Core\Program\Actions\ArchiveProgram;
use App\Core\Program\Actions\RestoreProgram;

final class ProgramController extends Controller
{
    public function __construct(
    private readonly CreateProgram $createProgram,
    private readonly UpdateProgram $updateProgram,
    private readonly ArchiveProgram $archiveProgram,
    private readonly RestoreProgram $restoreProgram,
    private readonly ProgramRepositoryInterface $repository,
) {
}

    public function store(
        StoreProgramRequest $request,
    ): JsonResponse {

        $program = $this->createProgram->execute(
            new CreateProgramData(
                organizationId: (int) $request->integer('organization_id'),
                programCode: $request->string('program_code')->toString(),
                title: $request->string('title')->toString(),
                description: $request->input('description'),
                programType: $request->string('program_type')->toString(),
                deliveryMode: $request->string('delivery_mode')->toString(),
                startsAt: $request->input('starts_at'),
                endsAt: $request->input('ends_at'),
                venue: $request->input('venue'),
                capacity: $request->input('capacity'),
                language: $request->string('language')->toString(),
                credentialEnabled: $request->boolean('credential_enabled'),
                createdBy: $request->input('created_by'),
            )
        );

        return (new ProgramResource($program))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): JsonResponse
{
    $program = $this->repository->findByUuid($uuid);

    if ($program === null) {
        return response()->json([
            'message' => 'Program not found.',
        ], 404);
    }

    return (new ProgramResource($program))
        ->response()
        ->setStatusCode(200);
}

public function index(): JsonResponse
{
    return ProgramResource::collection(
        $this->repository->paginate()
    )->response();
}

public function update(
    UpdateProgramRequest $request,
    string $uuid,
): JsonResponse {

    try {

        $program = $this->updateProgram->execute(
            uuid: $uuid,
            data: new UpdateProgramData(
                title: $request->string('title')->toString(),
                description: $request->input('description'),
                programType: $request->string('program_type')->toString(),
                deliveryMode: $request->string('delivery_mode')->toString(),
            )
        );

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Program not found.',
        ], 404);

    }

    return (new ProgramResource($program))
        ->response()
        ->setStatusCode(200);
}

public function destroy(string $uuid): JsonResponse
{
    try {

        $this->archiveProgram->execute($uuid);

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Program not found.',
        ], 404);

    }

    return response()->json([], 204);
}

public function restore(string $uuid): JsonResponse
{
    try {

        $program = $this->restoreProgram->execute($uuid);

    } catch (RuntimeException) {

        return response()->json([
            'message' => 'Program not found.',
        ], 404);

    }

    return (new ProgramResource($program))
        ->response()
        ->setStatusCode(200);
}

}