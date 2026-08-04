<?php

declare(strict_types=1);

namespace App\Presentation\Api\Credential\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCredentialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer'],
            'participant_id' => ['required', 'integer'],
            'program_id' => ['required', 'integer'],
            'session_id' => ['required', 'integer'],
            'template_id' => ['required', 'integer'],
            'credential_type' => ['required', 'string', 'max:50'],
            'expires_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}