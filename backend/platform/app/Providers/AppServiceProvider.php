<?php

namespace App\Providers;

use App\Core\Organization\Repositories\EloquentOrganizationRepository;
use App\Core\Organization\Repositories\OrganizationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            OrganizationRepositoryInterface::class,
            EloquentOrganizationRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
