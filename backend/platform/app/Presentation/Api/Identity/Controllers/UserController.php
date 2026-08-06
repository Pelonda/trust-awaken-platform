<?php

declare(strict_types=1);

namespace App\Presentation\Api\Identity\Controllers;

use App\Core\Identity\Repositories\UserRepository;
use App\Core\Identity\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly UserService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->users->paginate()
        );
    }

    public function show(string $uuid): JsonResponse
    {
        return response()->json([
            'data' => $this->users->findByUuid($uuid),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'organization_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'phone' => ['nullable', 'string'],
            'job_title' => ['nullable', 'string'],
            'user_type' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        return response()->json([
            'data' => $this->service->create($data),
        ], 201);
    }

    public function update(
        Request $request,
        string $uuid,
    ): JsonResponse {

        $user = $this->users->findByUuid($uuid);

        $data = $request->validate([
            'organization_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['nullable', 'min:8'],
            'phone' => ['nullable', 'string'],
            'job_title' => ['nullable', 'string'],
            'user_type' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        return response()->json([
            'data' => $this->service->update(
                $user,
                $data,
            ),
        ]);
    }

    public function destroy(
        string $uuid,
    ): JsonResponse {

        $user = $this->users->findByUuid($uuid);

        $this->users->delete($user);

        return response()->json([
            'message' => 'User deleted.',
        ]);
    }
}