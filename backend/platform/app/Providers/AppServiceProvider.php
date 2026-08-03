<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Identity\Repositories\EloquentUserRepository;
use App\Core\Identity\Repositories\UserRepositoryInterface;
use App\Core\Organization\Repositories\EloquentOrganizationRepository;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;
use App\Core\Program\Repositories\EloquentProgramRepository;
use App\Core\Program\Repositories\ProgramRepositoryInterface;
use App\Core\Participant\Repositories\EloquentParticipantRepository;
use App\Core\Participant\Repositories\ParticipantRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Core\Session\Repositories\EloquentSessionRepository;
use App\Core\Session\Repositories\SessionRepositoryInterface;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class,
        );

        $this->app->bind(
            OrganizationRepositoryInterface::class,
            EloquentOrganizationRepository::class,
        );

        $this->app->bind(
            ProgramRepositoryInterface::class,
            EloquentProgramRepository::class,
        );

        $this->app->bind(
    ParticipantRepositoryInterface::class,
    EloquentParticipantRepository::class,
);

$this->app->bind(
    SessionRepositoryInterface::class,
    EloquentSessionRepository::class,
);
    }

    public function boot(): void
    {
        //
    }
}