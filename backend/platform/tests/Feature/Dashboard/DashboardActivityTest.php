<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DashboardActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_recent_activity_endpoint(): void
    {
        $response = $this->getJson(
            '/api/v1/dashboard/recent-activity'
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'recent_credentials',
            'recent_participants',
            'recent_programs',
        ]);
    }
}