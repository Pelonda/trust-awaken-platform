<?php

declare(strict_types=1);

namespace App\Presentation\Api\ProgramEnrollment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreProgramEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant_uuid' => [
                'required',
                'uuid',
            ],

            'status' => [
                'sometimes',
                'string',

                Rule::in([
                    'enrolled',
                    'active',
                    'completed',
                    'withdrawn',
                    'cancelled',
                ]),
            ],

            'metadata' => [
                'sometimes',
                'nullable',
                'array',
            ],
        ];
    }
}