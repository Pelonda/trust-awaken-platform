<?php

declare(strict_types=1);

namespace App\Presentation\Api\ProgramEnrollment\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProgramEnrollmentResource extends JsonResource
{
    public function toArray(
        Request $request,
    ): array {
        return [
            'uuid' =>
                $this->uuid,

            'organization_id' =>
                $this->organization_id,

            'status' =>
                $this->status,

            'enrolled_at' =>
                $this->enrolled_at
                    ?->toISOString(),

            'completed_at' =>
                $this->completed_at
                    ?->toISOString(),

            'metadata' =>
                $this->metadata,

            'participant' => [
                'uuid' =>
                    $this->participant
                        ?->uuid,

                'participant_code' =>
                    $this->participant
                        ?->participant_code,

                'first_name' =>
                    $this->participant
                        ?->first_name,

                'last_name' =>
                    $this->participant
                        ?->last_name,

                'email' =>
                    $this->participant
                        ?->email,

                'phone' =>
                    $this->participant
                        ?->phone,

                'country' =>
                    $this->participant
                        ?->country,

                'status' =>
                    $this->participant
                        ?->status,
            ],

            'program' => [
                'uuid' =>
                    $this->program
                        ?->uuid,

                'program_code' =>
                    $this->program
                        ?->program_code,

                'title' =>
                    $this->program
                        ?->title,
            ],

            'created_at' =>
                $this->created_at
                    ?->toISOString(),

            'updated_at' =>
                $this->updated_at
                    ?->toISOString(),
        ];
    }
}