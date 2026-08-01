<?php

declare(strict_types=1);

use App\Presentation\Api\Identity\Controllers\IdentityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Identity API
|--------------------------------------------------------------------------
|
| Authentication and Identity Management.
|
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