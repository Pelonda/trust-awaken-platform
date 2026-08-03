<?php

declare(strict_types=1);

namespace App\Core\Attendance\Repositories;

use App\Core\Attendance\DTOs\CreateAttendanceData;
use App\Core\Attendance\Models\Attendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

final class EloquentAttendanceRepository implements AttendanceRepositoryInterface
{
    public function create(
        CreateAttendanceData $data
    ): Attendance {

        return Attendance::create([
            'uuid' => (string) Str::uuid(),
            'session_id' => $data->sessionId,
            'participant_id' => $data->participantId,
            'status' => $data->status,
            'checked_in_at' => $data->checkedInAt,
            'checked_out_at' => $data->checkedOutAt,
            'remarks' => $data->remarks,
        ]);
    }

    public function update(
        Attendance $attendance,
        array $attributes
    ): Attendance {

        $attendance->update($attributes);

        return $attendance->refresh();
    }

    public function delete(
        Attendance $attendance
    ): void {

        $attendance->delete();
    }

    public function restore(
        Attendance $attendance
    ): void {

        $attendance->restore();
    }

    public function findByUuid(
        string $uuid
    ): ?Attendance {

        return Attendance::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function findTrashedByUuid(
        string $uuid
    ): ?Attendance {

        return Attendance::onlyTrashed()
            ->where('uuid', $uuid)
            ->first();
    }

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator {

        return Attendance::query()
            ->paginate($perPage);
    }
}