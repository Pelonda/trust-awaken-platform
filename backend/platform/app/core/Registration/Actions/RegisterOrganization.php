<?php

declare(strict_types=1);

namespace App\Core\Registration\Actions;

use App\Core\Registration\DTOs\RegisterOrganizationData;
use App\Core\Registration\Services\RegistrationService;

final readonly class RegisterOrganization
{
    public function __construct(
        private RegistrationService $service,
    ) {
    }

    public function execute(
        RegisterOrganizationData $data,
    ): mixed {
        return $this->service->register($data);
    }
}