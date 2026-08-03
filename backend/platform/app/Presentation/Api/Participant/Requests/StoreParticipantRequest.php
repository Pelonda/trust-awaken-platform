<?php

declare(strict_types=1);

namespace App\Presentation\Api\Participant\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'organization_id' => ['required', 'integer'],

            'participant_code' => ['required', 'string', 'max:50'],

            'first_name' => ['required', 'string', 'max:100'],

            'last_name' => ['required', 'string', 'max:100'],

            'email' => ['nullable', 'email'],

            'phone' => ['nullable', 'string'],

            'date_of_birth' => ['nullable', 'date'],

            'gender' => ['nullable', 'string'],

            'country' => ['nullable', 'string'],
        ];
    }
}