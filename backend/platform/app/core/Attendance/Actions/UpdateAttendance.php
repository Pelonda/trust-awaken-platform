<?php

declare(strict_types=1);

namespace App\Core\Attendance\Actions;

use App\Core\Attendance\DTOs\UpdateAttendanceData;
use App\Core\Attendance\Models\Attendance;
use App\Core\Attendance\Repositories\AttendanceRepositoryInterface;
use RuntimeException;

final readonly class UpdateAttendance
{
    public function __construct(
        private AttendanceRepositoryInterface $repository,
    ) {
    }

    public function execute(
        string $uuid,
        UpdateAttendanceData $data,
    ): Attendance {

        $attendance = $this->repository->findByUuid($uuid);

        if ($attendance === null) {
            throw new RuntimeException('Attendance not found.');
        }

        return $this->repository->update(
            $attendance,
            [
                'status' => $data->status,
                'checked_in_at' => $data->checkedInAt,
                'checked_out_at' => $data->checkedOutAt,
                'remarks' => $data->remarks,
            ]
        );
    }
}