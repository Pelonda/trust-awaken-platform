<?php

declare(strict_types=1);

use App\Presentation\Api\Program\Controllers\ProgramController;
use App\Presentation\Api\Participant\Controllers\ParticipantController;
use App\Presentation\Api\Session\Controllers\SessionController;
use App\Presentation\Api\Attendance\Controllers\AttendanceController;
use App\Presentation\Api\CredentialTemplate\Controllers\CredentialTemplateController;
use App\Presentation\Api\Credential\Controllers\CredentialController;
use App\Presentation\Api\Verification\Controllers\VerificationController;
use App\Presentation\Api\Dashboard\Controllers\DashboardController;
use App\Presentation\Api\Dashboard\Controllers\DashboardActivityController;

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

Route::get(
    '',
    [SessionController::class, 'index']
);

Route::post(
    '',
    [SessionController::class, 'store']
);

Route::get(
    '{uuid}',
    [SessionController::class, 'show']
);

Route::put(
    '{uuid}',
    [SessionController::class, 'update']
);

Route::delete(
    '{uuid}',
    [SessionController::class, 'destroy']
);

Route::post(
    '{uuid}/restore',
    [SessionController::class, 'restore']
);

});

/*
|--------------------------------------------------------------------------
| Attendance API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/attendance')->group(function (): void {

    Route::get(
        '',
        [AttendanceController::class, 'index']
    );

    Route::post(
        '',
        [AttendanceController::class, 'store']
    );

    Route::get(
        '{uuid}',
        [AttendanceController::class, 'show']
    );

    Route::put(
        '{uuid}',
        [AttendanceController::class, 'update']
    );

    Route::delete(
        '{uuid}',
        [AttendanceController::class, 'destroy']
    );

    Route::post(
    '{uuid}/restore',
    [AttendanceController::class, 'restore']
);

});

/*
|--------------------------------------------------------------------------
| Credential Template API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/credential-templates')->group(function (): void {

    Route::get(
        '',
        [CredentialTemplateController::class, 'index']
    );

    Route::post(
        '',
        [CredentialTemplateController::class, 'store']
    );

    Route::get(
        '{uuid}',
        [CredentialTemplateController::class, 'show']
    );

    Route::put(
        '{uuid}',
        [CredentialTemplateController::class, 'update']
    );

    Route::delete(
        '{uuid}',
        [CredentialTemplateController::class, 'destroy']
    );

    Route::post(
        '{uuid}/restore',
        [CredentialTemplateController::class, 'restore']
    );

});

/*
|--------------------------------------------------------------------------
| Credential API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/credentials')->group(function (): void {

    Route::get(
        '',
        [CredentialController::class, 'index']
    );

    Route::post(
        '',
        [CredentialController::class, 'store']
    );

    Route::get(
        '{uuid}',
        [CredentialController::class, 'show']
    );

    Route::put(
        '{uuid}',
        [CredentialController::class, 'update']
    );

    Route::post(
    '{uuid}/revoke',
    [CredentialController::class, 'revoke']
);

Route::post(
    '{uuid}/restore',
    [CredentialController::class, 'restore']
);

});

/*
|--------------------------------------------------------------------------
| Public Verification API
|--------------------------------------------------------------------------
*/

Route::get(
    'v1/verify/{verificationCode}',
    [VerificationController::class, 'verify']
);

/*
|--------------------------------------------------------------------------
| Dashboard API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/dashboard')->group(function (): void {

    Route::get(
        'overview',
        [DashboardController::class, 'overview']
    );

});

/*
|--------------------------------------------------------------------------
| Dashboard API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/dashboard')->group(function (): void {

    Route::get(
        'overview',
        [DashboardController::class, 'overview']
    );

    Route::get(
        'recent-activity',
        [DashboardActivityController::class, 'index']
    );

});