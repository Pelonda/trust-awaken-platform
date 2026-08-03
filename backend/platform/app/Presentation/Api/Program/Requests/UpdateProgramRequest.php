<?php

declare(strict_types=1);

namespace App\Presentation\Api\Program\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProgramRequest extends FormRequest
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
            'program_type' => ['required', 'string'],
            'delivery_mode' => ['required', 'string'],
        ];
    }
}