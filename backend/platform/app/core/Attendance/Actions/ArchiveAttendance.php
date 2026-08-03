<?php

declare(strict_types=1);

namespace App\Core\Attendance\Actions;

use App\Core\Attendance\Repositories\AttendanceRepositoryInterface;
use RuntimeException;

final readonly class ArchiveAttendance
{
    public function __construct(
        private AttendanceRepositoryInterface $repository,
    ) {
    }

    public function execute(string $uuid): void
    {
        $attendance = $this->repository->findByUuid($uuid);

        if ($attendance === null) {
            throw new RuntimeException('Attendance not found.');
        }

        $this->repository->delete($attendance);
    }
}