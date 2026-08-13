<?php

declare(strict_types=1);

namespace App\Core\Credential\Repositories;

use App\Core\Credential\DTOs\CreateCredentialData;
use App\Core\Credential\Models\Credential;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CredentialRepositoryInterface
{
    public function create(
        CreateCredentialData $data
    ): Credential;

    public function update(
        Credential $credential,
        array $attributes
    ): Credential;

    public function delete(
        Credential $credential
    ): void;

    public function restore(
        Credential $credential
    ): void;

    public function findByUuid(
        string $uuid
    ): ?Credential;

    public function findTrashedByUuid(
        string $uuid
    ): ?Credential;

    public function paginate(
        int $perPage = 15
    ): LengthAwarePaginator;

    public function findByVerificationCode(
        string $verificationCode
    ): ?Credential;
}