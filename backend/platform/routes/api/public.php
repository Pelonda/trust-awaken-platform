<?php

declare(strict_types=1);

use App\Presentation\Api\Public\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/public')->group(function (): void {

    Route::post(
        'register',
        [RegistrationController::class, 'register']
    );

});