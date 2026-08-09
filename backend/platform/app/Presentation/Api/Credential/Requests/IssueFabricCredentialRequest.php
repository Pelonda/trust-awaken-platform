<?php

declare(strict_types=1);

namespace App\Presentation\Api\Credential\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class IssueFabricCredentialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant_uuid' => [
                'required',
                'uuid',
            ],

            'program_uuid' => [
                'required',
                'uuid',
            ],

            'session_uuid' => [
                'required',
                'uuid',
            ],

            'document_template_uuid' => [
                'required',
                'uuid',
            ],

            'credential_type' => [
                'required',
                'string',
                'max:50',
            ],

            'expires_at' => [
                'nullable',
                'date',
            ],

            'metadata' => [
                'nullable',
                'array',
            ],
        ];
    }
}