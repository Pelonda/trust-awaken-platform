<?php

declare(strict_types=1);

namespace Tests\Feature\Verification;

use App\Core\Credential\Models\Credential;
use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\Organization\Models\Organization;
use App\Core\Participant\Models\Participant;
use App\Core\Program\Models\Program;
use App\Core\Session\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class VerifyCredentialTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_existing_credential(): void
    {
        $owner = User::factory()->create();

        $organization = Organization::create([
            'uuid' => (string) Str::uuid(),
            'slug' => 'global-cybersafe',
            'display_name' => 'Global CyberSafe',
            'legal_name' => 'Global CyberSafe Foundation',
            'organization_type' => 'nonprofit',
            'status' => 'active',
            'owner_user_id' => $owner->id,
        ]);

        $program = Program::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'program_code' => 'GC-001',
            'title' => 'Cybersecurity Bootcamp',
            'program_type' => 'bootcamp',
            'delivery_mode' => 'in_person',
            'status' => 'draft',
            'language' => 'en',
            'credential_enabled' => true,
        ]);

        $session = Session::create([
            'uuid' => (string) Str::uuid(),
            'program_id' => $program->id,
            'session_code' => 'S-001',
            'title' => 'Day 1',
            'session_number' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addHours(8),
            'status' => 'scheduled',
        ]);

        $participant = Participant::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'participant_code' => 'P-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'status' => 'active',
        ]);

        $template = CredentialTemplate::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'template_code' => 'CERT-001',
            'name' => 'Certificate of Completion',
            'credential_type' => 'certificate',
            'paper_size' => 'A4',
            'orientation' => 'landscape',
            'is_default' => true,
        ]);

        Credential::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'participant_id' => $participant->id,
            'program_id' => $program->id,
            'session_id' => $session->id,
            'template_id' => $template->id,
            'credential_number' => 'CR-000001',
            'verification_code' => 'VERIFY-123456',
            'credential_type' => 'certificate',
            'status' => 'issued',
            'issued_at' => now(),
        ]);

        $response = $this->getJson(
            '/api/v1/verify/VERIFY-123456'
        );

        $response->assertOk();

        $response->assertJsonFragment([
            'valid' => true,
            'credential_number' => 'CR-000001',
            'credential_type' => 'certificate',
            'status' => 'issued',
        ]);
    }

    public function test_verify_unknown_credential_returns_404(): void
    {
        $response = $this->getJson(
            '/api/v1/verify/UNKNOWN'
        );

        $response->assertNotFound();

        $response->assertJson([
            'valid' => false,
            'message' => 'Credential not found.',
        ]);
    }
}