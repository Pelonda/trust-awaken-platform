<?php

declare(strict_types=1);

namespace App\Presentation\Api\Dashboard\Controllers;

use App\Core\Credential\Models\Credential;
use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

final class DashboardActivityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([

            'recent_credentials' => Credential::query()
                ->latest('issued_at')
                ->limit(10)
                ->get(),

            'recent_participants' => Participant::query()
                ->latest()
                ->limit(10)
                ->get(),

            'recent_programs' => Program::query()
                ->latest()
                ->limit(10)
                ->get(),

        ]);
    }
}