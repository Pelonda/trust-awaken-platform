<?php

declare(strict_types=1);

namespace App\Presentation\Api\Program\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'organization_id' => ['required', 'integer'],

            'program_code' => ['required', 'string', 'max:50'],

            'title' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'program_type' => ['required', 'string'],

            'delivery_mode' => ['required', 'string'],

            'starts_at' => ['nullable', 'date'],

            'ends_at' => ['nullable', 'date'],

            'venue' => ['nullable', 'string'],

            'capacity' => ['nullable', 'integer'],

            'language' => ['required', 'string'],

            'credential_enabled' => ['required', 'boolean'],

            'created_by' => ['nullable', 'integer'],
        ];
    }
}