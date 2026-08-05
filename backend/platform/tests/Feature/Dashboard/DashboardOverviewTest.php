<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DashboardOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_overview_endpoint(): void
    {
        $response = $this->getJson(
            '/api/v1/dashboard/overview'
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'organizations',
            'programs',
            'participants',
            'sessions',
            'attendance',
            'credentials',
            'issued_today',
            'revoked',
            'active_programs',
        ]);
    }
}