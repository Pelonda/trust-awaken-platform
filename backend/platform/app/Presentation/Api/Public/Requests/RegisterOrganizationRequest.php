<?php

declare(strict_types=1);

namespace App\Presentation\Api\Public\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RegisterOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'organization_name' => [
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

            'owner_name' => [
                'required',
                'string',
                'max:255',
            ],

            'owner_email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],

        ];
    }
}