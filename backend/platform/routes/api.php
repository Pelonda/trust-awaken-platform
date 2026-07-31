<?php

declare(strict_types=1);

use App\Presentation\Api\Identity\Controllers\IdentityController;
use App\Presentation\Api\Organization\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Identity API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/identity')->group(function (): void {

    Route::get(
        'users',
        [IdentityController::class, 'index']
    );

    Route::post(
        'users',
        [IdentityController::class, 'store']
    );

    Route::get(
        'users/{uuid}',
        [IdentityController::class, 'show']
    );

    Route::put(
        'users/{uuid}',
        [IdentityController::class, 'update']
    );

    Route::post(
        'users/{uuid}/deactivate',
        [IdentityController::class, 'deactivate']
    );

});

/*
|--------------------------------------------------------------------------
| Platform API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/platform')->group(function (): void {

    Route::get(
        'organizations',
        [OrganizationController::class, 'index']
    );

    Route::post(
        'organizations',
        [OrganizationController::class, 'store']
    );

    Route::get(
        'organizations/{uuid}',
        [OrganizationController::class, 'show']
    );

    Route::put(
        'organizations/{uuid}',
        [OrganizationController::class, 'update']
    );

    Route::delete(
        'organizations/{uuid}',
        [OrganizationController::class, 'destroy']
    );

    Route::post(
        'organizations/{uuid}/restore',
        [OrganizationController::class, 'restore']
    );

});