<?php

declare(strict_types=1);

namespace App\Presentation\Api\CredentialTemplate\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCredentialTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer'],
            'template_code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'credential_type' => ['required', 'string'],
            'paper_size' => ['required', 'string'],
            'orientation' => ['required', 'string'],
            'background_image' => ['nullable', 'string'],
            'elements' => ['nullable', 'array'],
            'is_default' => ['required', 'boolean'],
        ];
    }
}