<?php

declare(strict_types=1);

namespace App\Core\Attendance\Repositories;

use App\Core\Attendance\DTOs\CreateAttendanceData;
use App\Core\Attendance\Models\Attendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AttendanceRepositoryInterface
{
    public function create(
        CreateAttendanceData $data
    ): Attendance;

    public function update(
        Attendance $attendance,
        array $attributes
    ): Attendance;

    public function delete(
        Attendance $attendance
    ): void;

    public function restore(
        Attendance $attendance
    ): void;

    public function findByUuid(
        string $uuid
    ): ?Attendance;

    public function findTrashedByUuid(
        string $uuid
    ): ?Attendance;

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator;
}