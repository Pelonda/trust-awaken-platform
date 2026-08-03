<?php

declare(strict_types=1);

namespace App\Presentation\Api\Session\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Core\Session\Models\Session
 */
final class SessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'session_code' => $this->session_code,
            'title' => $this->title,
            'description' => $this->description,
            'session_number' => $this->session_number,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'venue' => $this->venue,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
