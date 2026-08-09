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
            'organization_id' => [
                'required',
                'integer',
                'exists:organizations,id',
            ],

            'participant_id' => [
                'required',
                'integer',
                'exists:participants,id',
            ],

            'program_id' => [
                'required',
                'integer',
                'exists:programs,id',
            ],

            'session_id' => [
                'required',
                'integer',
                'exists:program_sessions,id',
            ],

            /*
             * Legacy path.
             * Required only when Fabric template
             * is not being used.
             */
            'template_id' => [
                'nullable',
                'integer',
                'exists:credential_templates,id',
                'required_without:document_template_id',
                'prohibited_with:document_template_id',
            ],

            /*
             * Fabric Studio path.
             * Required only when legacy template
             * is not being used.
             */
            'document_template_id' => [
                'nullable',
                'integer',
                'exists:document_templates,id',
                'required_without:template_id',
                'prohibited_with:template_id',
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