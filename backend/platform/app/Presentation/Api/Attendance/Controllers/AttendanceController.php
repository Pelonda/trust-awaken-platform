<?php

declare(strict_types=1);

namespace App\Presentation\Api\Attendance\Controllers;

use App\Core\Attendance\Actions\ArchiveAttendance;
use App\Core\Attendance\Actions\CreateAttendance;
use App\Core\Attendance\Actions\RestoreAttendance;
use App\Core\Attendance\Actions\UpdateAttendance;
use App\Core\Attendance\DTOs\CreateAttendanceData;
use App\Core\Attendance\DTOs\UpdateAttendanceData;
use App\Core\Attendance\Repositories\AttendanceRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Attendance\Requests\StoreAttendanceRequest;
use App\Presentation\Api\Attendance\Requests\UpdateAttendanceRequest;
use App\Presentation\Api\Attendance\Resources\AttendanceResource;
use Illuminate\Http\JsonResponse;
use RuntimeException;

final class AttendanceController extends Controller
{
    public function __construct(
        private readonly CreateAttendance $createAttendance,
        private readonly UpdateAttendance $updateAttendance,
        private readonly ArchiveAttendance $archiveAttendance,
        private readonly RestoreAttendance $restoreAttendance,
        private readonly AttendanceRepositoryInterface $repository,
    ) {
    }

    public function index(): JsonResponse
    {
        return AttendanceResource::collection(
            $this->repository->paginate()
        )->response();
    }

    public function show(string $uuid): JsonResponse
    {
        $attendance = $this->repository->findByUuid($uuid);

        if ($attendance === null) {
            return response()->json([
                'message' => 'Attendance not found.',
            ], 404);
        }

        return (new AttendanceResource($attendance))
            ->response()
            ->setStatusCode(200);
    }

    public function store(
        StoreAttendanceRequest $request,
    ): JsonResponse {

        $attendance = $this->createAttendance->execute(
            new CreateAttendanceData(
                sessionId: $request->integer('session_id'),
                participantId: $request->integer('participant_id'),
                status: $request->string('status')->toString(),
                checkedInAt: $request->input('checked_in_at'),
                checkedOutAt: $request->input('checked_out_at'),
                remarks: $request->input('remarks'),
            )
        );

        return (new AttendanceResource($attendance))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdateAttendanceRequest $request,
        string $uuid,
    ): JsonResponse {

        try {

            $attendance = $this->updateAttendance->execute(
                uuid: $uuid,
                data: new UpdateAttendanceData(
                    status: $request->string('status')->toString(),
                    checkedInAt: $request->input('checked_in_at'),
                    checkedOutAt: $request->input('checked_out_at'),
                    remarks: $request->input('remarks'),
                )
            );

        } catch (RuntimeException) {

            return response()->json([
                'message' => 'Attendance not found.',
            ], 404);

        }

        return (new AttendanceResource($attendance))
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(string $uuid): JsonResponse
    {
        try {

            $this->archiveAttendance->execute($uuid);

        } catch (RuntimeException) {

            return response()->json([
                'message' => 'Attendance not found.',
            ], 404);

        }

        return response()->json([], 204);
    }

    public function restore(string $uuid): JsonResponse
    {
        try {

            $attendance = $this->restoreAttendance->execute($uuid);

        } catch (RuntimeException) {

            return response()->json([
                'message' => 'Attendance not found.',
            ], 404);

        }

        return (new AttendanceResource($attendance))
            ->response()
            ->setStatusCode(200);
    }
}