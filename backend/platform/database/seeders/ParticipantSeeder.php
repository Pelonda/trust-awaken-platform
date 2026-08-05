<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Organization\Models\Organization;
use App\Core\Participant\Models\Participant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class ParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();

        if (! $organization) {
            return;
        }

        for ($i = 1; $i <= 50; $i++) {

            Participant::updateOrCreate(
                [
                    'participant_code' => sprintf('P%04d', $i),
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'organization_id' => $organization->id,
                    'participant_code' => sprintf('P%04d', $i),
                    'first_name' => "Student{$i}",
                    'last_name' => 'Demo',
                    'email' => "student{$i}@globalcybersafe.org",
                    'phone' => '0000000000',
                    'gender' => 'male',
                    'country' => 'DR Congo',
                    'status' => 'active',
                ]
            );

        }
    }
}