<?php

declare(strict_types=1);

namespace App\Presentation\Api\Session\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}