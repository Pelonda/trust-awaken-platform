<?php

declare(strict_types=1);

namespace App\Presentation\Api\Credential\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCredentialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string'],
        ];
    }
}