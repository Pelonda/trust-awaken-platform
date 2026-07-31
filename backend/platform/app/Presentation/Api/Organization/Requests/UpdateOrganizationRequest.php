<?php

declare(strict_types=1);

namespace App\Presentation\Api\Organization\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'display_name' => [
                'required',
                'string',
                'max:255',
            ],

            'legal_name' => [
                'required',
                'string',
                'max:255',
            ],

            'organization_type' => [
                'required',
                'string',
            ],
        ];
    }
}