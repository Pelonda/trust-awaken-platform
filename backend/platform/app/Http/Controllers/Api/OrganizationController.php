<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class OrganizationController extends Controller
{
    public function store(): JsonResponse
    {
        return response()->json([
            'message' => 'Coming Soon'
        ]);
    }
}