<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Presentation\Api\Program\Controllers\ProgramController;

use App\Presentation\Api\Participant\Controllers\ParticipantController;
use App\Presentation\Api\Participant\Controllers\ParticipantImportController;

use App\Presentation\Api\Session\Controllers\SessionController;
use App\Presentation\Api\Attendance\Controllers\AttendanceController;

use App\Presentation\Api\ProgramEnrollment\Controllers\ProgramEnrollmentController;

use App\Presentation\Api\CredentialTemplate\Controllers\CredentialTemplateController;

use App\Presentation\Api\Credential\Controllers\CredentialController;
use App\Presentation\Api\Credential\Controllers\FabricCredentialController;
use App\Presentation\Api\Credential\Controllers\DownloadCredentialController;
use App\Presentation\Api\Credential\Controllers\PreviewCredentialController;

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

        /*
        |--------------------------------------------------------------------------
        | Program Collection
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Program Enrollments
        |--------------------------------------------------------------------------
        |
        | Enrollment operations are organization-scoped.
        |
        | These routes must remain before the generic {uuid}
        | program routes.
        |
        */

        Route::middleware(
            'organization'
        )->group(
            function (): void {

                /*
                |--------------------------------------------------------------------------
                | List Enrollments
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '{programUuid}/enrollments',
                    [
                        ProgramEnrollmentController::class,
                        'index',
                    ]
                )
                    ->whereUuid(
                        'programUuid'
                    );


                /*
                |--------------------------------------------------------------------------
                | Enroll One Participant
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '{programUuid}/enrollments',
                    [
                        ProgramEnrollmentController::class,
                        'store',
                    ]
                )
                    ->whereUuid(
                        'programUuid'
                    );


                /*
                |--------------------------------------------------------------------------
                | Bulk Enrollment
                |--------------------------------------------------------------------------
                |
                | Keep /bulk before the dynamic enrollment UUID routes.
                |
                */

                Route::post(
                    '{programUuid}/enrollments/bulk',
                    [
                        ProgramEnrollmentController::class,
                        'bulk',
                    ]
                )
                    ->whereUuid(
                        'programUuid'
                    );


                /*
                |--------------------------------------------------------------------------
                | Update Enrollment
                |--------------------------------------------------------------------------
                */

                Route::put(
                    '{programUuid}/enrollments/{enrollmentUuid}',
                    [
                        ProgramEnrollmentController::class,
                        'update',
                    ]
                )
                    ->whereUuid(
                        'programUuid'
                    )
                    ->whereUuid(
                        'enrollmentUuid'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Enrollment
                |--------------------------------------------------------------------------
                */

                Route::delete(
                    '{programUuid}/enrollments/{enrollmentUuid}',
                    [
                        ProgramEnrollmentController::class,
                        'destroy',
                    ]
                )
                    ->whereUuid(
                        'programUuid'
                    )
                    ->whereUuid(
                        'enrollmentUuid'
                    );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Program Resource
        |--------------------------------------------------------------------------
        */

        Route::get(
            '{uuid}',
            [
                ProgramController::class,
                'show',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::put(
            '{uuid}',
            [
                ProgramController::class,
                'update',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::delete(
            '{uuid}',
            [
                ProgramController::class,
                'destroy',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::post(
            '{uuid}/restore',
            [
                ProgramController::class,
                'restore',
            ]
        )->whereUuid(
            'uuid'
        );
    });


/*
|--------------------------------------------------------------------------
| Participant API
|--------------------------------------------------------------------------
*/

Route::prefix('v1/participants')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Participant Collection
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Participant Import
        |--------------------------------------------------------------------------
        |
        | Preview:
        |
        | Parses and validates CSV/XLSX/XLS without modifying participant
        | or enrollment records.
        |
        | Commit:
        |
        | Creates or updates participants and automatically enrolls each
        | successfully imported participant into the selected program.
        |
        | Import routes MUST remain above the generic {uuid} routes.
        |
        */

        Route::middleware(
            'organization'
        )->group(
            function (): void {

                /*
                |--------------------------------------------------------------------------
                | Preview Import
                |--------------------------------------------------------------------------
                */

                Route::post(
                    'import/preview',
                    [
                        ParticipantImportController::class,
                        'preview',
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | Commit Import
                |--------------------------------------------------------------------------
                */

                Route::post(
                    'import/commit',
                    [
                        ParticipantImportController::class,
                        'commit',
                    ]
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Participant Resource
        |--------------------------------------------------------------------------
        */

        Route::get(
            '{uuid}',
            [
                ParticipantController::class,
                'show',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::put(
            '{uuid}',
            [
                ParticipantController::class,
                'update',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::delete(
            '{uuid}',
            [
                ParticipantController::class,
                'destroy',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::post(
            '{uuid}/restore',
            [
                ParticipantController::class,
                'restore',
            ]
        )->whereUuid(
            'uuid'
        );
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
        )->whereUuid(
            'uuid'
        );

        Route::put(
            '{uuid}',
            [
                SessionController::class,
                'update',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::delete(
            '{uuid}',
            [
                SessionController::class,
                'destroy',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::post(
            '{uuid}/restore',
            [
                SessionController::class,
                'restore',
            ]
        )->whereUuid(
            'uuid'
        );
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
        )->whereUuid(
            'uuid'
        );

        Route::put(
            '{uuid}',
            [
                AttendanceController::class,
                'update',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::delete(
            '{uuid}',
            [
                AttendanceController::class,
                'destroy',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::post(
            '{uuid}/restore',
            [
                AttendanceController::class,
                'restore',
            ]
        )->whereUuid(
            'uuid'
        );
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
        )->whereUuid(
            'uuid'
        );


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
        )->whereUuid(
            'uuid'
        );

        Route::put(
            '{uuid}',
            [
                CredentialTemplateController::class,
                'update',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::delete(
            '{uuid}',
            [
                CredentialTemplateController::class,
                'destroy',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::post(
            '{uuid}/restore',
            [
                CredentialTemplateController::class,
                'restore',
            ]
        )->whereUuid(
            'uuid'
        );
    });


/*
|--------------------------------------------------------------------------
| Credential API
|--------------------------------------------------------------------------
|
| Static and specialized routes are kept before the generic {uuid}
| resource routes.
|
*/

Route::prefix('v1/credentials')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Credential Collection
        |--------------------------------------------------------------------------
        */

        Route::get(
            '',
            [
                CredentialController::class,
                'index',
            ]
        );

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
        | Fabric Studio credentials use document_template_id.
        | Legacy credentials continue using template_id.
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
        | Credential Documents
        |--------------------------------------------------------------------------
        */

        Route::get(
            '{uuid}/preview',
            PreviewCredentialController::class
        )->whereUuid(
            'uuid'
        );

        Route::get(
            '{uuid}/download',
            DownloadCredentialController::class
        )->whereUuid(
            'uuid'
        );


        /*
        |--------------------------------------------------------------------------
        | Credential Lifecycle
        |--------------------------------------------------------------------------
        */

        Route::post(
            '{uuid}/revoke',
            [
                CredentialController::class,
                'revoke',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::post(
            '{uuid}/restore',
            [
                CredentialController::class,
                'restore',
            ]
        )->whereUuid(
            'uuid'
        );


        /*
        |--------------------------------------------------------------------------
        | Credential Resource
        |--------------------------------------------------------------------------
        */

        Route::get(
            '{uuid}',
            [
                CredentialController::class,
                'show',
            ]
        )->whereUuid(
            'uuid'
        );

        Route::put(
            '{uuid}',
            [
                CredentialController::class,
                'update',
            ]
        )->whereUuid(
            'uuid'
        );
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
| Tenant templates:
|
| /api/document-templates
|
| Professional system library:
|
| /api/document-templates/library
|
*/

Route::prefix('document-templates')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Professional Template Library
        |--------------------------------------------------------------------------
        |
        | These MUST remain above the dynamic {document_template} route.
        |
        */

        Route::get(
            'library',
            [
                DocumentTemplateController::class,
                'library',
            ]
        );

        Route::post(
            '{id}/use',
            [
                DocumentTemplateController::class,
                'useTemplate',
            ]
        )->whereNumber(
            'id'
        );


        /*
        |--------------------------------------------------------------------------
        | Tenant Templates
        |--------------------------------------------------------------------------
        */

        Route::get(
            '',
            [
                DocumentTemplateController::class,
                'index',
            ]
        );

        Route::post(
            '',
            [
                DocumentTemplateController::class,
                'store',
            ]
        );

        Route::get(
            '{document_template}',
            [
                DocumentTemplateController::class,
                'show',
            ]
        )->whereNumber(
            'document_template'
        );

        Route::match(
            [
                'PUT',
                'PATCH',
            ],
            '{document_template}',
            [
                DocumentTemplateController::class,
                'update',
            ]
        )->whereNumber(
            'document_template'
        );

        Route::delete(
            '{document_template}',
            [
                DocumentTemplateController::class,
                'destroy',
            ]
        )->whereNumber(
            'document_template'
        );
    });