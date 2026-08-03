<?php

declare(strict_types=1);

namespace App\Presentation\Api\CredentialTemplate\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Core\CredentialTemplate\Models\CredentialTemplate
 */
final class CredentialTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'template_code' => $this->template_code,
            'name' => $this->name,
            'credential_type' => $this->credential_type,
            'paper_size' => $this->paper_size,
            'orientation' => $this->orientation,
            'background_image' => $this->background_image,
            'elements' => $this->elements,
            'is_default' => $this->is_default,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}