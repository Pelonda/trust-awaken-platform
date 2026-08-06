<?php

declare(strict_types=1);

use App\Presentation\Api\Document\Controllers\DocumentTemplateController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/documents')->middleware([
    'auth:sanctum',
])->group(function (): void {

    Route::get(
        'templates',
        [DocumentTemplateController::class, 'index']
    );

    Route::post(
        'templates',
        [DocumentTemplateController::class, 'store']
    );

    Route::get(
        'templates/{uuid}',
        [DocumentTemplateController::class, 'show']
    );

    Route::put(
        'templates/{uuid}',
        [DocumentTemplateController::class, 'update']
    );

    Route::delete(
        'templates/{uuid}',
        [DocumentTemplateController::class, 'destroy']
    );

});