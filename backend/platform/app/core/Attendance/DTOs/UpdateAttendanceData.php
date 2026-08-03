<?php

declare(strict_types=1);

namespace App\Core\Attendance\DTOs;

final readonly class UpdateAttendanceData
{
    public function __construct(
        public string $status,
        public ?string $checkedInAt,
        public ?string $checkedOutAt,
        public ?string $remarks,
    ) {
    }
}