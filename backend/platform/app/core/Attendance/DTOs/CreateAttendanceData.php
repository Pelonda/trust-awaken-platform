<?php

declare(strict_types=1);

namespace App\Core\Attendance\DTOs;

final readonly class CreateAttendanceData
{
    public function __construct(
        public int $sessionId,
        public int $participantId,
        public string $status,
        public ?string $checkedInAt,
        public ?string $checkedOutAt,
        public ?string $remarks,
    ) {
    }
}
