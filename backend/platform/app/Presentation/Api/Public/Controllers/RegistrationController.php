<?php

declare(strict_types=1);

namespace App\Presentation\Api\Public\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class RegistrationController extends Controller
{
    public function register(): JsonResponse
    {
        return response()->json([
            'message' => 'Registration workflow not implemented yet.',
        ], 501);
    }
}