<?php

declare(strict_types=1);

use App\Presentation\Api\Organization\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

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

});