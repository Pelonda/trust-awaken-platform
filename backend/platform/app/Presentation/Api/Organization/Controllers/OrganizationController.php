<?php

declare(strict_types=1);

namespace App\Presentation\Api\Organization\Controllers;

use App\Presentation\Api\Organization\Requests\StoreOrganizationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class OrganizationController extends Controller
{
    public function store(): JsonResponse
    {
        return response()->json([
            'message' => 'Not implemented.',
        ], 501);
    }
}