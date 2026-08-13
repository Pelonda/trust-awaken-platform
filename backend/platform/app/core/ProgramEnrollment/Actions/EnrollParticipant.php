<?php

declare(strict_types=1);

namespace App\Core\ProgramEnrollment\Actions;

use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use App\Core\ProgramEnrollment\Models\ProgramEnrollment;
use Illuminate\Support\Str;
use RuntimeException;

final class EnrollParticipant
{
    public function execute(
        Program $program,
        Participant $participant,
        string $status = 'enrolled',
        ?array $metadata = null,
    ): ProgramEnrollment {
        if (
            (int) $program->organization_id !==
            (int) $participant->organization_id
        ) {
            throw new RuntimeException(
                'The participant and program must belong to the same organization.'
            );
        }

        $enrollment =
            ProgramEnrollment::query()
                ->where(
                    'organization_id',
                    $program->organization_id
                )
                ->where(
                    'program_id',
                    $program->id
                )
                ->where(
                    'participant_id',
                    $participant->id
                )
                ->first();

        if ($enrollment !== null) {
            return $enrollment;
        }

        return ProgramEnrollment::query()->create([
            'uuid' =>
                (string) Str::uuid(),

            'organization_id' =>
                $program->organization_id,

            'program_id' =>
                $program->id,

            'participant_id' =>
                $participant->id,

            'status' =>
                $status,

            'enrolled_at' =>
                now(),

            'completed_at' =>
                $status === 'completed'
                    ? now()
                    : null,

            'metadata' =>
                $metadata,
        ]);
    }
}