<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        Organization::firstOrCreate(
            ['slug' => 'global-cybersafe'],
            [
                'uuid' => (string) Str::uuid(),
                'display_name' => 'Global CyberSafe',
                'legal_name' => 'Global CyberSafe Foundation',
                'organization_type' => 'nonprofit',
                'status' => 'active',
            ]
        );
    }
}