<?php

declare(strict_types=1);

namespace App\Core\Attendance\Actions;

use App\Core\Attendance\DTOs\CreateAttendanceData;
use App\Core\Attendance\Models\Attendance;
use App\Core\Attendance\Repositories\AttendanceRepositoryInterface;

final readonly class CreateAttendance
{
    public function __construct(
        private AttendanceRepositoryInterface $repository,
    ) {
    }

    public function execute(
        CreateAttendanceData $data,
    ): Attendance {

        return $this->repository->create($data);
    }
}