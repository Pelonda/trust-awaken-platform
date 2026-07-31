<?php

declare(strict_types=1);

namespace App\Presentation\Api\Identity\Controllers;

use App\Core\Identity\Actions\CreateUser;
use App\Core\Identity\Actions\DeactivateUser;
use App\Core\Identity\Actions\UpdateUser;
use App\Core\Identity\DTOs\CreateUserData;
use App\Core\Identity\DTOs\UpdateUserData;
use App\Core\Identity\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Identity\Requests\StoreUserRequest;
use App\Presentation\Api\Identity\Requests\UpdateUserRequest;
use App\Presentation\Api\Identity\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use RuntimeException;

final class IdentityController extends Controller
{
    public function __construct(
        private readonly CreateUser $createUser,
        private readonly UpdateUser $updateUser,
        private readonly DeactivateUser $deactivateUser,
        private readonly UserRepositoryInterface $users,
    ) {
    }

    public function index(): JsonResponse
    {
        return UserResource::collection(
            $this->users->paginate()
        )->response();
    }

    public function show(string $uuid): JsonResponse
    {
        $user = $this->users->findByUuid($uuid);

        if ($user === null) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->createUser->execute(
            new CreateUserData(
                name: $request->string('name')->toString(),
                email: $request->string('email')->toString(),
                password: $request->string('password')->toString(),
            )
        );

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdateUserRequest $request,
        string $uuid,
    ): JsonResponse {
        try {
            $user = $this->updateUser->execute(
                uuid: $uuid,
                data: new UpdateUserData(
                    name: $request->string('name')->toString(),
                    email: $request->string('email')->toString(),
                ),
            );
        } catch (RuntimeException) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }

    public function deactivate(string $uuid): JsonResponse
    {
        try {
            $user = $this->deactivateUser->execute($uuid);
        } catch (RuntimeException) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }
}