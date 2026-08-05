<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Organization\Models\Organization;
use App\Core\Program\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();

        if (! $organization) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {

            Program::updateOrCreate(
                [
                    'program_code' => sprintf('GCS%03d', $i),
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'organization_id' => $organization->id,
                    'title' => "Cybersecurity Bootcamp {$i}",
                    'description' => "Training Program {$i}",
                    'program_type' => 'bootcamp',
                    'delivery_mode' => 'in_person',
                    'status' => 'active',
                    'language' => 'en',
                    'credential_enabled' => true,
                ]
            );

        }
    }
}