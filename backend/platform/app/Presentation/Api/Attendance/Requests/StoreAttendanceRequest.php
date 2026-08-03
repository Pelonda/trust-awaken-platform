<?php

declare(strict_types=1);

namespace App\Presentation\Api\Attendance\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id' => ['required', 'integer'],
            'participant_id' => ['required', 'integer'],
            'status' => ['required', 'string'],
            'checked_in_at' => ['nullable', 'date'],
            'checked_out_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}