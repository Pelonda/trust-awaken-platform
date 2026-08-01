<?php

declare(strict_types=1);

namespace App\Presentation\Api\Identity\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
final class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'uuid' => $this->uuid,

            'name' => $this->name,

            'email' => $this->email,

            'user_type' => $this->user_type,

            'status' => $this->status,

            'email_verified_at' => $this->email_verified_at,

            'last_login_at' => $this->last_login_at,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

        ];
    }
}