<?php

declare(strict_types=1);

use App\Presentation\Api\Program\Controllers\ProgramController;

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