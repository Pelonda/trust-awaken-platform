<?php

declare(strict_types=1);

namespace App\Presentation\Api\Participant\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'gender' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
        ];
    }
}