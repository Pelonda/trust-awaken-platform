<?php

declare(strict_types=1);

use App\Presentation\Api\Program\Controllers\ProgramController;
use App\Presentation\Api\Participant\Controllers\ParticipantController;
use App\Presentation\Api\Session\Controllers\SessionController;

/*
|--------------------------------------------------------------------------
| Trust AWAKEN API
|--------------------------------------------------------------------------
|
| This file only bootstraps the module route files.
| Each module owns its own routes.
|
*/

require __DIR__ . '/api/public.php';
require __DIR__ . '/api/identity.php';
require __DIR__ . '/api/organization.php';

// Future Modules
// require __DIR__ . '/api/billing.php';
// require __DIR__ . '/api/document.php';
// require __DIR__ . '/api/template.php';
// require __DIR__ . '/api/workshop.php';
// require __DIR__ . '/api/participant.php';
// require __DIR__ . '/api/certificate.php';
// require __DIR__ . '/api/verification.php';
// require __DIR__ . '/api/reporting.php';
// require __DIR__ . '/api/marketplace.php';

/*
|--------------------------------------------------------------------------
| Program API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/programs')->group(function (): void {

    Route::post(
        '',
        [ProgramController::class, 'store']
    );

    Route::get(
    '{uuid}',
    [ProgramController::class, 'show']
);

    Route::get(
    '',
    [ProgramController::class, 'index']
);

Route::put(
    '{uuid}',
    [ProgramController::class, 'update']
);

Route::delete(
    '{uuid}',
    [ProgramController::class, 'destroy']
);

Route::post(
    '{uuid}/restore',
    [ProgramController::class, 'restore']
);

});

/*
|--------------------------------------------------------------------------
| Participant API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/participants')->group(function (): void {

    Route::post(
        '',
        [ParticipantController::class, 'store']
    );

    Route::get(
    '{uuid}',
    [ParticipantController::class, 'show']
);

Route::get(
    '',
    [ParticipantController::class, 'index']
);

Route::put(
    '{uuid}',
    [ParticipantController::class, 'update']
);

Route::delete(
    '{uuid}',
    [ParticipantController::class, 'destroy']
);

Route::post(
    '{uuid}/restore',
    [ParticipantController::class, 'restore']
);

});

/*
|--------------------------------------------------------------------------
| Session API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/sessions')->group(function (): void {

    Route::post(
        '',
        [SessionController::class, 'store']
    );

    Route::get(
    '{uuid}',
    [SessionController::class, 'show']
);

});