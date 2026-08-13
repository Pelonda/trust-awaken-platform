<?php

declare(strict_types=1);

namespace App\Presentation\Api\ProgramEnrollment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateProgramEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',

                Rule::in([
                    'enrolled',
                    'active',
                    'completed',
                    'withdrawn',
                    'cancelled',
                ]),
            ],
        ];
    }
}