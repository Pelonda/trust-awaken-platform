<?php

declare(strict_types=1);

namespace App\Presentation\Api\Public\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

final class RegistrationController extends Controller
{
    public function register(): JsonResponse
    {
        return response()->json([
            'message' => 'Registration endpoint ready.',
        ], 200);
    }
}