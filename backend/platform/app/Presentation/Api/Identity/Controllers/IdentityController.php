<?php

declare(strict_types=1);

namespace App\Presentation\Api\Identity\Controllers;

use App\Core\Identity\Actions\CreateUser;
use App\Core\Identity\DTOs\CreateUserData;
use App\Core\Identity\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Identity\Requests\StoreUserRequest;
use App\Presentation\Api\Identity\Resources\UserResource;
use Illuminate\Http\JsonResponse;

final class IdentityController extends Controller
{
    public function __construct(
        private readonly CreateUser $createUser,
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
}