<?php

declare(strict_types=1);

use App\Presentation\Api\Auth\Controllers\AuthController;
use App\Presentation\Api\Identity\Controllers\IdentityController;
use App\Presentation\Api\Identity\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Identity & Authentication API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/identity')->group(function (): void {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post(
        'login',
        [AuthController::class, 'login']
    );

    Route::post(
        'logout',
        [AuthController::class, 'logout']
    )->middleware('auth:sanctum');

    Route::get(
        'me',
        [AuthController::class, 'me']
    )->middleware('auth:sanctum');

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    Route::middleware([
        'auth:sanctum',
        'permission:user.view',
    ])->group(function (): void {

        Route::get(
            'users',
            [UserController::class, 'index']
        );

        Route::get(
            'users/{uuid}',
            [UserController::class, 'show']
        );

    });

    Route::middleware([
        'auth:sanctum',
        'permission:user.create',
    ])->post(
        'users',
        [UserController::class, 'store']
    );

    Route::middleware([
        'auth:sanctum',
        'permission:user.update',
    ])->put(
        'users/{uuid}',
        [UserController::class, 'update']
    );

    Route::middleware([
        'auth:sanctum',
        'permission:user.delete',
    ])->delete(
        'users/{uuid}',
        [UserController::class, 'destroy']
    );

});