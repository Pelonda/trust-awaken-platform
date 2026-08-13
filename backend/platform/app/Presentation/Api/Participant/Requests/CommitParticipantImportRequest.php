<?php

declare(strict_types=1);

namespace App\Presentation\Api\Participant\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CommitParticipantImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:csv,txt,xlsx,xls',
                'max:10240',
            ],

            'program_uuid' => [
                'required',
                'uuid',
            ],

            'update_existing' => [
                'sometimes',
                'boolean',
            ],

            'enrollment_status' => [
                'sometimes',
                'string',
                'in:enrolled,active,completed',
            ],
        ];
    }
}