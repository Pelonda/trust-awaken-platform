<?php

declare(strict_types=1);

namespace App\Presentation\Api\Session\Controllers;

use App\Core\Session\Actions\ArchiveSession;
use App\Core\Session\Actions\CreateSession;
use App\Core\Session\Actions\RestoreSession;
use App\Core\Session\Actions\UpdateSession;
use App\Core\Session\DTOs\CreateSessionData;
use App\Core\Session\DTOs\UpdateSessionData;
use App\Core\Session\Repositories\SessionRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Session\Requests\StoreSessionRequest;
use App\Presentation\Api\Session\Requests\UpdateSessionRequest;
use App\Presentation\Api\Session\Resources\SessionResource;
use Illuminate\Http\JsonResponse;
use RuntimeException;

final class SessionController extends Controller
{
    public function __construct(
        private readonly CreateSession $createSession,
        private readonly UpdateSession $updateSession,
        private readonly ArchiveSession $archiveSession,
        private readonly RestoreSession $restoreSession,
        private readonly SessionRepositoryInterface $repository,
    ) {
    }

    public function index(): JsonResponse
    {
        return SessionResource::collection(
            $this->repository->paginate()
        )->response();
    }

    public function show(string $uuid): JsonResponse
    {
        $session = $this->repository->findByUuid($uuid);

        if ($session === null) {
            return response()->json([
                'message' => 'Session not found.',
            ], 404);
        }

        return (new SessionResource($session))
            ->response()
            ->setStatusCode(200);
    }

    public function store(
        StoreSessionRequest $request,
    ): JsonResponse {

        $session = $this->createSession->execute(
            new CreateSessionData(
                programId: $request->integer('program_id'),
                sessionCode: $request->string('session_code')->toString(),
                title: $request->string('title')->toString(),
                description: $request->input('description'),
                sessionNumber: $request->integer('session_number'),
                startsAt: $request->string('starts_at')->toString(),
                endsAt: $request->string('ends_at')->toString(),
                venue: $request->input('venue'),
            )
        );

        return (new SessionResource($session))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdateSessionRequest $request,
        string $uuid,
    ): JsonResponse {

        try {

            $session = $this->updateSession->execute(
                uuid: $uuid,
                data: new UpdateSessionData(
                    title: $request->string('title')->toString(),
                    description: $request->input('description'),
                )
            );

        } catch (RuntimeException) {

            return response()->json([
                'message' => 'Session not found.',
            ], 404);

        }

        return (new SessionResource($session))
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(string $uuid): JsonResponse
    {
        try {

            $this->archiveSession->execute($uuid);

        } catch (RuntimeException) {

            return response()->json([
                'message' => 'Session not found.',
            ], 404);

        }

        return response()->json([], 204);
    }

    public function restore(string $uuid): JsonResponse
    {
        try {

            $session = $this->restoreSession->execute($uuid);

        } catch (RuntimeException) {

            return response()->json([
                'message' => 'Session not found.',
            ], 404);

        }

        return (new SessionResource($session))
            ->response()
            ->setStatusCode(200);
    }
}