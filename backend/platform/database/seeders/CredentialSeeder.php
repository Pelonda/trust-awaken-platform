<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Credential\Models\Credential;
use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\Organization\Models\Organization;
use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use App\Core\Session\Models\Session;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class CredentialSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();
        $template = CredentialTemplate::first();

        if (! $organization || ! $template) {
            return;
        }

        $participants = Participant::take(50)->get();

        foreach ($participants as $participant) {

            $program = Program::inRandomOrder()->first();
            $session = Session::where('program_id', $program->id)->first();

            Credential::updateOrCreate(
                [
                    'participant_id' => $participant->id,
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'organization_id' => $organization->id,
                    'participant_id' => $participant->id,
                    'program_id' => $program->id,
                    'session_id' => $session->id,
                    'template_id' => $template->id,
                    'credential_number' => 'CR-' . str_pad((string) $participant->id, 6, '0', STR_PAD_LEFT),
                    'verification_code' => (string) Str::uuid(),
                    'credential_type' => 'certificate',
                    'status' => 'issued',
                    'issued_at' => now(),
                ]
            );

        }
    }
}