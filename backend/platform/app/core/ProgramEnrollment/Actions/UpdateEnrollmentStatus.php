<?php

declare(strict_types=1);

namespace App\Core\ProgramEnrollment\Actions;

use App\Core\ProgramEnrollment\Models\ProgramEnrollment;

final class UpdateEnrollmentStatus
{
    public function execute(
        ProgramEnrollment $enrollment,
        string $status,
    ): ProgramEnrollment {
        $enrollment->status =
            $status;

        if (
            $status ===
            'completed'
        ) {
            $enrollment->completed_at =
                $enrollment->completed_at
                ?? now();
        } else {
            $enrollment->completed_at =
                null;
        }

        $enrollment->save();

        return $enrollment->fresh(
            [
                'participant',
                'program',
            ]
        );
    }
}