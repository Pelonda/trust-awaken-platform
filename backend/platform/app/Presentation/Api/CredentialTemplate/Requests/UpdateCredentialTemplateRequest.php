<?php

declare(strict_types=1);

namespace App\Presentation\Api\CredentialTemplate\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCredentialTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'paper_size' => ['required', 'string'],
            'orientation' => ['required', 'string'],
            'is_default' => ['required', 'boolean'],
        ];
    }
}