<?php

declare(strict_types=1);

use App\Presentation\Api\Auth\Controllers\AuthController;
use App\Presentation\Api\Identity\Controllers\IdentityController;
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

Route::get(
    'role-test',
    function () {
        return response()->json([
            'message' => 'Role Middleware Working',
        ]);
    }
)->middleware([
    'auth:sanctum',
    'role:super_admin',
]);

Route::get(
    'permission-test',
    function () {
        return response()->json([
            'message' => 'Permission Middleware Working',
        ]);
    }
)->middleware([
    'auth:sanctum',
    'permission:dashboard.view',
]);