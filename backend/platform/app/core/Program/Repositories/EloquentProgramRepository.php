<?php

declare(strict_types=1);

namespace App\Core\Program\Repositories;

use App\Core\Program\DTOs\CreateProgramData;
use App\Core\Program\Models\Program;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

final class EloquentProgramRepository implements ProgramRepositoryInterface
{
    public function create(
        CreateProgramData $data
    ): Program {

        return Program::create([

            'uuid' => (string) Str::uuid(),

            'organization_id' => $data->organizationId,

            'program_code' => $data->programCode,

            'title' => $data->title,

            'description' => $data->description,

            'program_type' => $data->programType,

            'delivery_mode' => $data->deliveryMode,

            'status' => 'draft',

            'starts_at' => $data->startsAt,

            'ends_at' => $data->endsAt,

            'venue' => $data->venue,

            'capacity' => $data->capacity,

            'language' => $data->language,

            'credential_enabled' => $data->credentialEnabled,

            'created_by' => $data->createdBy,

        ]);
    }

    public function update(
        Program $program,
        array $attributes
    ): Program {

        $program->update($attributes);

        return $program->refresh();
    }

    public function delete(
        Program $program
    ): void {

        $program->delete();
    }

    public function restore(
        Program $program
    ): void {

        $program->restore();
    }

    public function findByUuid(
        string $uuid
    ): ?Program {

        return Program::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator {

        return Program::query()
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function findTrashedByUuid(
    string $uuid
): ?Program {

    return Program::onlyTrashed()
        ->where('uuid', $uuid)
        ->first();
}


}