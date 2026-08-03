<?php

declare(strict_types=1);

namespace App\Presentation\Api\Program\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Core\Program\Models\Program
 */
final class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'uuid' => $this->uuid,

            'program_code' => $this->program_code,

            'title' => $this->title,

            'description' => $this->description,

            'program_type' => $this->program_type,

            'delivery_mode' => $this->delivery_mode,

            'status' => $this->status,

            'starts_at' => $this->starts_at,

            'ends_at' => $this->ends_at,

            'venue' => $this->venue,

            'capacity' => $this->capacity,

            'language' => $this->language,

            'credential_enabled' => $this->credential_enabled,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

        ];
    }
}