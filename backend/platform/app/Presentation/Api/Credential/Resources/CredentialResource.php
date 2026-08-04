<?php

declare(strict_types=1);

namespace App\Presentation\Api\Credential\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Core\Credential\Models\Credential
 */
final class CredentialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'credential_number' => $this->credential_number,
            'verification_code' => $this->verification_code,
            'credential_type' => $this->credential_type,
            'status' => $this->status,
            'issued_at' => $this->issued_at,
            'expires_at' => $this->expires_at,
            'revoked_at' => $this->revoked_at,
            'pdf_path' => $this->pdf_path,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}