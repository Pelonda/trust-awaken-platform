<?php

declare(strict_types=1);

use App\Presentation\Api\Organization\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/platform')->group(function (): void {
    Route::post(
        'organizations',
        [OrganizationController::class, 'store']
    );
});