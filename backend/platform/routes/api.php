<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Presentation\Api\Program\Controllers\ProgramController;
use App\Presentation\Api\Participant\Controllers\ParticipantController;
use App\Presentation\Api\Session\Controllers\SessionController;
use App\Presentation\Api\Attendance\Controllers\AttendanceController;
use App\Presentation\Api\CredentialTemplate\Controllers\CredentialTemplateController;
use App\Presentation\Api\Credential\Controllers\CredentialController;
use App\Presentation\Api\Credential\Controllers\FabricCredentialController;
use App\Presentation\Api\Credential\Controllers\DownloadCredentialController;
use App\Presentation\Api\Verification\Controllers\VerificationController;
use App\Presentation\Api\Dashboard\Controllers\DashboardController;
use App\Presentation\Api\Dashboard\Controllers\DashboardActivityController;

use App\Http\Controllers\Api\DocumentTemplateController;
use App\Http\Controllers\Api\DocumentBrandController;
use App\Http\Controllers\Api\DocumentAssetController;


/*
|--------------------------------------------------------------------------
| Trust AWAKEN API
|--------------------------------------------------------------------------
|
| Module-owned route files.
|
*/

require __DIR__ . '/api/public.php';
require __DIR__ . '/api/identity.php';
require __DIR__ . '/api/organization.php';
require __DIR__ . '/api/document.php';


/*
|--------------------------------------------------------------------------
| Program API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/programs')
    ->group(function (): void {

        Route::get(
            '',
            [
                ProgramController::class,
                'index',
            ]
        );

        Route::post(
            '',
            [
                ProgramController::class,
                'store',
            ]
        );

        Route::get(
            '{uuid}',
            [
                ProgramController::class,
                'show',
            ]
        )->whereUuid('uuid');

        Route::put(
            '{uuid}',
            [
                ProgramController::class,
                'update',
            ]
        )->whereUuid('uuid');

        Route::delete(
            '{uuid}',
            [
                ProgramController::class,
                'destroy',
            ]
        )->whereUuid('uuid');

        Route::post(
            '{uuid}/restore',
            [
                ProgramController::class,
                'restore',
            ]
        )->whereUuid('uuid');
    });


/*
|--------------------------------------------------------------------------
| Participant API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/participants')
    ->group(function (): void {

        Route::get(
            '',
            [
                ParticipantController::class,
                'index',
            ]
        );

        Route::post(
            '',
            [
                ParticipantController::class,
                'store',
            ]
        );

        Route::get(
            '{uuid}',
            [
                ParticipantController::class,
                'show',
            ]
        )->whereUuid('uuid');

        Route::put(
            '{uuid}',
            [
                ParticipantController::class,
                'update',
            ]
        )->whereUuid('uuid');

        Route::delete(
            '{uuid}',
            [
                ParticipantController::class,
                'destroy',
            ]
        )->whereUuid('uuid');

        Route::post(
            '{uuid}/restore',
            [
                ParticipantController::class,
                'restore',
            ]
        )->whereUuid('uuid');
    });


/*
|--------------------------------------------------------------------------
| Session API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/sessions')
    ->group(function (): void {

        Route::get(
            '',
            [
                SessionController::class,
                'index',
            ]
        );

        Route::post(
            '',
            [
                SessionController::class,
                'store',
            ]
        );

        Route::get(
            '{uuid}',
            [
                SessionController::class,
                'show',
            ]
        )->whereUuid('uuid');

        Route::put(
            '{uuid}',
            [
                SessionController::class,
                'update',
            ]
        )->whereUuid('uuid');

        Route::delete(
            '{uuid}',
            [
                SessionController::class,
                'destroy',
            ]
        )->whereUuid('uuid');

        Route::post(
            '{uuid}/restore',
            [
                SessionController::class,
                'restore',
            ]
        )->whereUuid('uuid');
    });


/*
|--------------------------------------------------------------------------
| Attendance API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/attendance')
    ->group(function (): void {

        Route::get(
            '',
            [
                AttendanceController::class,
                'index',
            ]
        );

        Route::post(
            '',
            [
                AttendanceController::class,
                'store',
            ]
        );

        Route::get(
            '{uuid}',
            [
                AttendanceController::class,
                'show',
            ]
        )->whereUuid('uuid');

        Route::put(
            '{uuid}',
            [
                AttendanceController::class,
                'update',
            ]
        )->whereUuid('uuid');

        Route::delete(
            '{uuid}',
            [
                AttendanceController::class,
                'destroy',
            ]
        )->whereUuid('uuid');

        Route::post(
            '{uuid}/restore',
            [
                AttendanceController::class,
                'restore',
            ]
        )->whereUuid('uuid');
    });


/*
|--------------------------------------------------------------------------
| Credential Template API
|--------------------------------------------------------------------------
|
| Static routes MUST remain above the dynamic {uuid} routes.
|
*/

Route::prefix('v1/credential-templates')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Document Brand
        |--------------------------------------------------------------------------
        */

        Route::get(
            'document-brand',
            [
                DocumentBrandController::class,
                'show',
            ]
        );

        Route::put(
            'document-brand',
            [
                DocumentBrandController::class,
                'save',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Document Assets
        |--------------------------------------------------------------------------
        */

        Route::get(
            'document-assets',
            [
                DocumentAssetController::class,
                'index',
            ]
        );

        Route::post(
            'document-assets',
            [
                DocumentAssetController::class,
                'store',
            ]
        );

        Route::delete(
            'document-assets/{uuid}',
            [
                DocumentAssetController::class,
                'destroy',
            ]
        )->whereUuid('uuid');


        /*
        |--------------------------------------------------------------------------
        | Legacy Credential Templates
        |--------------------------------------------------------------------------
        */

        Route::get(
            '',
            [
                CredentialTemplateController::class,
                'index',
            ]
        );

        Route::post(
            '',
            [
                CredentialTemplateController::class,
                'store',
            ]
        );

        Route::get(
            '{uuid}',
            [
                CredentialTemplateController::class,
                'show',
            ]
        )->whereUuid('uuid');

        Route::put(
            '{uuid}',
            [
                CredentialTemplateController::class,
                'update',
            ]
        )->whereUuid('uuid');

        Route::delete(
            '{uuid}',
            [
                CredentialTemplateController::class,
                'destroy',
            ]
        )->whereUuid('uuid');

        Route::post(
            '{uuid}/restore',
            [
                CredentialTemplateController::class,
                'restore',
            ]
        )->whereUuid('uuid');
    });


/*
|--------------------------------------------------------------------------
| Credential API
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| issue-fabric is a static route and MUST remain above {uuid}.
|
*/

Route::prefix('v1/credentials')
    ->group(function (): void {

        Route::get(
            '',
            [
                CredentialController::class,
                'index',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Legacy / General Credential Creation
        |--------------------------------------------------------------------------
        */

        Route::post(
            '',
            [
                CredentialController::class,
                'store',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Fabric Credential Issuance
        |--------------------------------------------------------------------------
        |
        | New Fabric Studio credentials use:
        |
        | document_template_id
        |
        | Legacy credentials continue using:
        |
        | template_id
        |
        */

        Route::post(
            'issue-fabric',
            [
                FabricCredentialController::class,
                'store',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Credential UUID Routes
        |--------------------------------------------------------------------------
        */

        Route::get(
            '{uuid}',
            [
                CredentialController::class,
                'show',
            ]
        )->whereUuid('uuid');

        Route::put(
            '{uuid}',
            [
                CredentialController::class,
                'update',
            ]
        )->whereUuid('uuid');

        Route::post(
            '{uuid}/revoke',
            [
                CredentialController::class,
                'revoke',
            ]
        )->whereUuid('uuid');

        Route::post(
            '{uuid}/restore',
            [
                CredentialController::class,
                'restore',
            ]
        )->whereUuid('uuid');

        Route::get(
            '{uuid}/download',
            DownloadCredentialController::class
        )->whereUuid('uuid');
    });


/*
|--------------------------------------------------------------------------
| Public Verification API
|--------------------------------------------------------------------------
*/

Route::get(
    'v1/verify/{verificationCode}',
    [
        VerificationController::class,
        'verify',
    ]
);


/*
|--------------------------------------------------------------------------
| Dashboard API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/dashboard')
    ->group(function (): void {

        Route::get(
            'overview',
            [
                DashboardController::class,
                'overview',
            ]
        );

        Route::get(
            'recent-activity',
            [
                DashboardActivityController::class,
                'index',
            ]
        );
    });


/*
|--------------------------------------------------------------------------
| Document Studio Template API
|--------------------------------------------------------------------------
|
| Fabric Document Studio currently uses:
|
| /api/document-templates
|
*/

Route::apiResource(
    'document-templates',
    DocumentTemplateController::class
);