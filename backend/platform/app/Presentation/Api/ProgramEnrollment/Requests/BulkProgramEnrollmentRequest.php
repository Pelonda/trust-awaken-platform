<?php

declare(strict_types=1);

namespace App\Presentation\Api\ProgramEnrollment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class BulkProgramEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant_uuids' => [
                'required',
                'array',
                'min:1',
                'max:1000',
            ],

            'participant_uuids.*' => [
                'required',
                'uuid',
                'distinct',
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