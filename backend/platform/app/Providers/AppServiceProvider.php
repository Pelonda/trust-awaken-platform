<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Identity\Repositories\EloquentUserRepository;
use App\Core\Identity\Repositories\UserRepositoryInterface;
use App\Core\Organization\Repositories\EloquentOrganizationRepository;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            OrganizationRepositoryInterface::class,
            EloquentOrganizationRepository::class,
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class,
        );
    }

    public function boot(): void
    {
        //
    }
}