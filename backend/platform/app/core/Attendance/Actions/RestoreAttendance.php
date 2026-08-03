<?php

declare(strict_types=1);

namespace App\Core\Attendance\Actions;

use App\Core\Attendance\Models\Attendance;
use App\Core\Attendance\Repositories\AttendanceRepositoryInterface;
use RuntimeException;

final readonly class RestoreAttendance
{
    public function __construct(
        private AttendanceRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): Attendance
    {
        $attendance = $this->repository->findTrashedByUuid($uuid);

        if ($attendance === null) {
            throw new RuntimeException('Attendance not found.');
        }

        $this->repository->restore($attendance);

        return $attendance->refresh();
    }
}