<?php

declare(strict_types=1);

namespace App\Core\Credential\Repositories;

use App\Core\Credential\DTOs\CreateCredentialData;
use App\Core\Credential\Models\Credential;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

final class EloquentCredentialRepository implements CredentialRepositoryInterface
{
    public function create(
        CreateCredentialData $data
    ): Credential {
        return Credential::create([
            'uuid' => (string) Str::uuid(),

            'organization_id' => $data->organizationId,
            'participant_id' => $data->participantId,
            'program_id' => $data->programId,
            'session_id' => $data->sessionId,

            /*
             * Keep the legacy template slot
             * for old credentials.
             */
            'template_id' => $data->templateId,

            /*
             * New Fabric Studio template slot.
             */
            'document_template_id' => $data->documentTemplateId,

            'credential_number' =>
                'CR-' . strtoupper(Str::random(12)),

            'verification_code' =>
                (string) Str::uuid(),

            'credential_type' => $data->credentialType,
            'status' => 'issued',
            'issued_at' => now(),
            'expires_at' => $data->expiresAt,
            'metadata' => $data->metadata,
        ]);
    }

    public function update(
        Credential $credential,
        array $attributes
    ): Credential {
        $credential->update($attributes);

        return $credential->refresh();
    }

    public function delete(
        Credential $credential
    ): void {
        $credential->delete();
    }

    public function restore(
        Credential $credential
    ): void {
        $credential->restore();
    }

    public function findByUuid(
        string $uuid
    ): ?Credential {
        return Credential::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function findTrashedByUuid(
        string $uuid
    ): ?Credential {
        return Credential::onlyTrashed()
            ->where('uuid', $uuid)
            ->first();
    }

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator {
        return Credential::query()
            ->orderByDesc('issued_at')
            ->paginate($perPage);
    }

    public function findByVerificationCode(
        string $verificationCode
    ): ?Credential {
        return Credential::query()
            ->where('verification_code', $verificationCode)
            ->first();
    }
}