<?php

declare(strict_types=1);

namespace App\Core\ProgramEnrollment\Actions;

use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use App\Core\ProgramEnrollment\Models\ProgramEnrollment;
use Illuminate\Support\Collection;

final class BulkEnrollParticipants
{
    public function __construct(
        private readonly EnrollParticipant $enrollParticipant,
    ) {
    }

    /**
     * @param array<int, string> $participantUuids
     *
     * @return Collection<int, ProgramEnrollment>
     */
    public function execute(
        Program $program,
        array $participantUuids,
        string $status = 'enrolled',
        ?array $metadata = null,
    ): Collection {
        $participants =
            Participant::query()
                ->where(
                    'organization_id',
                    $program->organization_id
                )
                ->whereIn(
                    'uuid',
                    $participantUuids
                )
                ->get();

        return $participants
            ->map(
                fn (
                    Participant $participant
                ): ProgramEnrollment =>
                    $this->enrollParticipant
                        ->execute(
                            program: $program,
                            participant: $participant,
                            status: $status,
                            metadata: $metadata,
                        )
            )
            ->values();
    }
}