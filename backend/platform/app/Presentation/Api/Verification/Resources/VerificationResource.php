<?php

declare(strict_types=1);

namespace App\Presentation\Api\Verification\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Core\Credential\Models\Credential
 */
final class VerificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'valid' => $this->status === 'issued',

            'credential_number' => $this->credential_number,

            'credential_type' => $this->credential_type,

            'status' => $this->status,

            'issued_at' => $this->issued_at,

            'expires_at' => $this->expires_at,

            'revoked_at' => $this->revoked_at,
        ];
    }
}