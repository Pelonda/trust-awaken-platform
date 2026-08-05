<?php

declare(strict_types=1);

namespace App\Presentation\Api\Dashboard\Controllers;

use App\Core\Attendance\Models\Attendance;
use App\Core\Credential\Models\Credential;
use App\Core\Organization\Models\Organization;
use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use App\Core\Session\Models\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class DashboardController extends Controller
{
    public function overview(): JsonResponse
    {
        return response()->json([
            'organizations' => Organization::count(),
            'programs' => Program::count(),
            'participants' => Participant::count(),
            'sessions' => Session::count(),
            'attendance' => Attendance::count(),
            'credentials' => Credential::count(),

            'issued_today' => Credential::query()
                ->whereDate('issued_at', today())
                ->count(),

            'revoked' => Credential::query()
                ->where('status', 'revoked')
                ->count(),

            'active_programs' => Program::query()
                ->where('status', 'active')
                ->count(),
        ]);
    }
}