<?php

declare(strict_types=1);

namespace App\Presentation\Api\Session\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_id' => ['required', 'integer'],
            'session_code' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'session_number' => ['required', 'integer'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date'],
            'venue' => ['nullable', 'string'],
        ];
    }
}